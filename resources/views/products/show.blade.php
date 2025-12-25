<x-layouts.app :title="$product->name">
    <div class="flex h-full w-full flex-1 flex-col gap-4">

        {{-- Session Flash Messages --}}
        @if(session('status'))
        <div class="mb-4 rounded-lg border border-green-300 bg-green-100 px-4 py-3 text-green-800 dark:border-green-900 dark:bg-green-900 dark:text-green-200">
            {{ session('status') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-300 bg-red-100 px-4 py-3 text-red-800 dark:border-red-900 dark:bg-red-900 dark:text-red-200">
            {{ session('error') }}
        </div>
        @endif

        <div>
            <flux:link
                :href="route('products.index')"
                wire:navigate
                class="mb-4 inline-flex items-center gap-2 text-sm">
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
                {{ __("Back to Products") }}
            </flux:link>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $product->name }}
                        </h1>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __("Product Details") }}
                        </p>
                    </div>

                    <div>
                        <form
                            method="POST"
                            action="{{ route("shopping-cart.add", $product) }}">
                            @csrf
                            <flux:button type="submit" variant="primary">
                                {{ __("Add to Cart") }}
                            </flux:button>
                        </form>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <flux:button
                        :href="route('products.edit', $product)"
                        variant="primary"
                        wire:navigate>
                        {{ __("Edit") }}
                    </flux:button>
                </div>
            </div>
        </div>

        <div
            class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6">
            <div class="space-y-6">
                <div>
                    <flux:text
                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __("Product Name") }}
                    </flux:text>
                    <flux:text class="mt-1 text-lg">
                        {{ $product->name }}
                    </flux:text>
                </div>

                <div>
                    <flux:text
                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __("Price") }}
                    </flux:text>
                    <flux:text class="mt-1 text-lg font-semibold">
                        ${{ number_format($product->price, 2) }}
                    </flux:text>
                </div>

                <div>
                    <flux:text
                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __("Stock Quantity") }}
                    </flux:text>
                    <flux:text class="mt-1 text-lg">
                        {{ $product->stock_quantity }}
                    </flux:text>
                </div>

                <div>
                    <flux:text
                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __("Created At") }}
                    </flux:text>
                    <flux:text class="mt-1 text-lg">
                        {{ $product->created_at->format("Y-m-d H:i:s") }}
                    </flux:text>
                </div>

                <div>
                    <flux:text
                        class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        {{ __("Last Updated") }}
                    </flux:text>
                    <flux:text class="mt-1 text-lg">
                        {{ $product->updated_at->format("Y-m-d H:i:s") }}
                    </flux:text>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <form
                method="POST"
                action="{{ route("products.destroy", $product) }}"
                class="inline"
                onsubmit="
                    return confirm(
                        '{{ __("Are you sure you want to delete this product?") }}',
                    );
                ">
                @csrf
                @method("DELETE")
                <flux:button
                    type="submit"
                    variant="ghost"
                    class="!text-red-600 dark:!text-red-400">
                    {{ __("Delete Product") }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts.app>