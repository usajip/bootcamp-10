<?php

use App\Models\Order;
use App\Models\User;

function makeAdminOrder(User $owner, array $overrides = []): Order
{
    return Order::create(array_merge([
        'order_number' => 'INV-ADMIN-'.uniqid(),
        'total_amount' => 150000,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'shipping_address' => 'Jl. Merdeka No. 1, Jakarta',
        'user_id' => $owner->id,
    ], $overrides));
}

function makeAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('allows an admin to see another user order', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $this->actingAs(makeAdmin())
        ->get(route('orders.show', $order->order_number))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Simpan Perubahan');
});

it('shows the plain status to the order owner instead of the admin form', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $this->actingAs($owner)
        ->get(route('orders.show', $order->order_number))
        ->assertOk()
        ->assertDontSee('Simpan Perubahan');
});

it('allows an admin to update the order status and payment status', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $response = $this->actingAs(makeAdmin())->patch(route('orders.update', $order), [
        'status' => 'completed',
        'payment_status' => 'paid',
    ]);

    $response->assertRedirect(route('orders.show', $order->order_number))
        ->assertSessionHas('success');

    expect($order->fresh())
        ->status->toBe('completed')
        ->payment_status->toBe('paid');
});

it('rejects invalid status values', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $this->actingAs(makeAdmin())
        ->patch(route('orders.update', $order), [
            'status' => 'shipped',
            'payment_status' => 'paid',
        ])
        ->assertSessionHasErrors('status');

    expect($order->fresh())
        ->status->toBe('pending')
        ->payment_status->toBe('unpaid');
});

it('forbids non admin users from updating an order', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $this->actingAs($owner)
        ->patch(route('orders.update', $order), [
            'status' => 'completed',
            'payment_status' => 'paid',
        ])
        ->assertForbidden();

    expect($order->fresh())->status->toBe('pending');
});

it('redirects guests away from updating an order', function () {
    $owner = User::factory()->create();
    $order = makeAdminOrder($owner);

    $this->patch(route('orders.update', $order), [
        'status' => 'completed',
        'payment_status' => 'paid',
    ])->assertRedirect(route('login'));

    expect($order->fresh())->status->toBe('pending');
});
