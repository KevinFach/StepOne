<?php

namespace Tests\Feature;

use App\Filament\Resources\Administrators\Pages\CreateAdministrator;
use App\Filament\Resources\Carts\Pages\EditCart;
use App\Filament\Resources\Carts\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Sneakers\Pages\CreateSneaker;
use App\Filament\Resources\Sneakers\Pages\EditSneaker;
use App\Filament\Resources\Sneakers\RelationManagers\VariantsRelationManager;
use App\Models\Cart;
use App\Models\Costumer;
use App\Models\Sneaker;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function admin(): Costumer
    {
        return Costumer::where('email', 'admin@stepone.test')->firstOrFail();
    }

    private function client(): Costumer
    {
        return Costumer::where('email', 'cliente@stepone.test')->firstOrFail();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_client_cannot_access_panel(): void
    {
        $this->actingAs($this->client())->get('/admin')->assertForbidden();
    }

    public function test_admin_can_log_in(): void
    {
        Livewire::test(Login::class)
            ->fillForm(['email' => 'admin@stepone.test', 'password' => 'password'])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($this->admin());
    }

    public function test_every_admin_page_renders(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk();

        foreach (Filament::getPanel('admin')->getResources() as $resource) {
            $this->get($resource::getUrl('index'))->assertOk();
            $this->get($resource::getUrl('create'))->assertOk();

            $record = $resource::getEloquentQuery()->first();
            if ($record) {
                $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();
            }
        }
    }

    public function test_admin_creates_sneaker_with_variant(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        Livewire::test(CreateSneaker::class)
            ->fillForm([
                'name' => 'Air Jordan 1',
                'estado' => Sneaker::ACTIVE,
                'idBrand' => 1,
                'idCategory' => 1,
                'image' => UploadedFile::fake()->image('jordan.png'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $sneaker = Sneaker::where('name', 'Air Jordan 1')->firstOrFail();
        Storage::disk('public')->assertExists($sneaker->image);
        $this->assertStringStartsWith('sneakers/', $sneaker->image);

        Livewire::test(VariantsRelationManager::class, [
            'ownerRecord' => $sneaker,
            'pageClass' => EditSneaker::class,
        ])
            ->callTableAction('create', data: [
                'size' => '26',
                'gender' => 'Man',
                'color' => 'Rojo',
                'price' => 3500,
                'stock' => 5,
            ])
            ->assertHasNoTableActionErrors();

        $this->assertDatabaseHas('sneakerVariant', [
            'idSneaker' => $sneaker->idSneaker,
            'color' => 'Rojo',
            'size' => '26',
        ]);

        $this->get('/productos')->assertSee('Air Jordan 1 (Rojo)');
    }

    public function test_client_and_admin_resources_force_is_admin(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateClient::class)
            ->fillForm([
                'email' => 'nuevo@test.mx', 'password' => 'secret123',
                'first_name' => 'Nuevo', 'last_name' => 'Cliente', 'phone' => '5533333333',
                'rfc' => 'NUCL000101AB1', 'CURP' => 'NUCL000101HDFXXX01', 'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $cliente = Costumer::where('email', 'nuevo@test.mx')->firstOrFail();
        $this->assertFalse($cliente->is_admin);
        $this->assertTrue(Hash::check('secret123', $cliente->password));

        Livewire::test(CreateAdministrator::class)
            ->fillForm([
                'email' => 'jefe@test.mx', 'password' => 'secret123',
                'first_name' => 'Jefe', 'last_name' => 'Admin', 'phone' => '5544444444',
                'rfc' => 'JEAD000101AB1', 'CURP' => 'JEAD000101HDFXXX01', 'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Costumer::where('email', 'jefe@test.mx')->firstOrFail()->is_admin);
    }

    public function test_editing_client_without_password_keeps_it(): void
    {
        $this->actingAs($this->admin());
        $cliente = $this->client();
        $hashAnterior = $cliente->password;

        Livewire::test(EditClient::class, ['record' => $cliente->getRouteKey()])
            ->fillForm(['first_name' => 'Renombrado', 'password' => ''])
            ->call('save')
            ->assertHasNoFormErrors();

        $cliente->refresh();
        $this->assertSame('Renombrado', $cliente->first_name);
        $this->assertSame($hashAnterior, $cliente->password);
    }

    public function test_cart_detail_takes_price_from_variant(): void
    {
        $this->actingAs($this->admin());
        $cart = Cart::create(['idCostumer' => $this->client()->idCostumer, 'status' => 'active', 'expiresAt' => now()->addWeek()]);

        Livewire::test(DetailsRelationManager::class, [
            'ownerRecord' => $cart,
            'pageClass' => EditCart::class,
        ])
            ->callTableAction('create', data: ['idSneakerVariant' => 1, 'quantity' => 2])
            ->assertHasNoTableActionErrors();

        $detalle = $cart->details()->firstOrFail();
        $this->assertSame(2, $detalle->quantity);
        $this->assertEquals($detalle->variant->price, $detalle->priceAtAddition);
    }
}
