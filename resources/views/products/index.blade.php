<x-layouts.app :title="__('Products')">
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
        @endif

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('ID') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Price') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Stock Quantity') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Created At') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-zinc-900">
                        @forelse ($products as $product)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800">
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">{{ $product->id }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <flux:link :href="route('products.show', $product)" wire:navigate class="text-accent hover:underline">
                                    {{ $product->name }}
                                </flux:link>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">${{ number_format($product->price, 2) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-900 dark:text-neutral-100">{{ $product->stock_quantity }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-500 dark:text-neutral-400">{{ $product->created_at->format('Y-m-d H:i') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button :href="route('products.show', $product)" variant="ghost" size="sm" wire:navigate>
                                        {{ __('View') }}
                                    </flux:button>
                                    <flux:button :href="route('products.edit', $product)" variant="ghost" size="sm" wire:navigate>
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" class="!text-red-600 dark:!text-red-400">
                                            {{ __('Delete') }}
                                        </flux:button>
                                    </form>
                                </div>
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

            @if ($products->hasPages())
            <div class="border-t border-neutral-200 dark:border-neutral-700 px-4 py-3">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>