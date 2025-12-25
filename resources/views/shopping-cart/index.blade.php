<x-layouts.app :title="__('Shopping Cart')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('Products') }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Manage your products') }}</p>
            </div>
            <flux:button :href="route('products.create')" variant="primary" wire:navigate>
                {{ __('Create Product') }}
            </flux:button>
        </div>

        @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            {{ session('status') }}
        </div>
        @elseif (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('ID') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Price per item') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Quantity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Total Price') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-zinc-900">
                        @forelse ($shoppingCartProducts as $shoppingCartProduct)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">{{ $shoppingCartProduct->id }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <flux:link :href="route('products.show', $shoppingCartProduct->product)" wire:navigate class="text-accent hover:underline">
                                    {{ $shoppingCartProduct->name }}
                                </flux:link>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">${{ number_format($shoppingCartProduct->price, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                                <form method="POST" action="{{ route('shopping-cart.update', $shoppingCartProduct->id) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        name="quantity_update"
                                        value="decrease"
                                        class="px-2 py-1 text-xs rounded bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 font-bold text-neutral-700 dark:text-neutral-200"
                                        type="submit">-</button>
                                    <span>{{ $shoppingCartProduct->quantity }}</span>
                                    <button
                                        name="quantity_update"
                                        value="increase"
                                        class="px-2 py-1 text-xs rounded bg-zinc-200 dark:bg-zinc-700 hover:bg-zinc-300 dark:hover:bg-zinc-600 font-bold text-neutral-700 dark:text-neutral-200"
                                        type="submit">+</button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">{{ $shoppingCartProduct->total_price ?? '' }}</td>

                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <form method="POST" action="{{ route('shopping-cart.remove', $shoppingCartProduct->id) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to remove this product from your cart?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" variant="ghost" size="sm">
                                        {{ __('Remove') }}
                                    </flux:button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                {{ __('No products found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-6 py-4">
            <flux:button :href="route('checkout.index')" variant="primary" wire:navigate>
                {{ __('Checkout') }}
            </flux:button>
        </div>
    </div>
    </div>
</x-layouts.app>