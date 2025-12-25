<x-layouts.app :title="__('Shopping Cart')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ __('Checkout') }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">For User {{ $name }}</p>
            </div>
        </div>

        @if (session("status"))
        <div class="bg-green-500 text-white p-4 rounded-lg">
            {{ session("status") }}
        </div>
        @elseif (session("error"))
        <div class="bg-red-500 text-white p-4 rounded-lg">
            {{ session("error") }}
        </div>
        @endif

        @if ($shoppingCartProducts->count() > 0)
        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Price per item') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Quantity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Total Price') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-zinc-900">
                        @forelse ($shoppingCartProducts as $shoppingCartProduct)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800">
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <flux:link :href="route('products.show', $shoppingCartProduct->product)" wire:navigate class="text-accent hover:underline">
                                    {{ $shoppingCartProduct->name }}
                                </flux:link>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">${{ number_format($shoppingCartProduct->price, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100 flex items-center gap-2">
                                {{ $shoppingCartProduct->quantity }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">{{ $shoppingCartProduct->total_price ?? '' }}</td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        <div class="py-4">
            <p class="text-lg font-bold text-white">Total Price: ${{ number_format($totalPrice, 2) }}</p>
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="shopping_cart_id" value="{{ $shoppingCartId }}">
                <flux:button type="submit" variant="primary">
                    {{ __('Process Payment') }}
                </flux:button>
            </form>
        </div>
        @else
        <div class="py-4">
            <p class="text-lg font-bold text-white">No products found.</p>
        </div>
        @endif
    </div>
</x-layouts.app>