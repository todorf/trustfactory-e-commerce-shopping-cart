<x-layouts.app :title="__('Create Product')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div>
            <flux:link
                :href="route('products.index')"
                wire:navigate
                class="mb-4 inline-flex items-center gap-2 text-sm"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                {{ __("Back to Products") }}
            </flux:link>
            <h1 class="text-2xl font-bold">{{ __("Create Product") }}</h1>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                {{ __("Add a new product to your store") }}
            </p>
        </div>

        <div
            class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6"
        >
            <form
                method="POST"
                action="{{ route("products.store") }}"
                class="space-y-6"
            >
                @csrf

                <flux:input
                    name="name"
                    :label="__('Product Name')"
                    :value="old('name')"
                    type="text"
                    required
                    autofocus
                    :placeholder="__('Enter product name')"
                    :error="$errors->first('name')"
                />

                <flux:input
                    name="price"
                    :label="__('Price')"
                    :value="old('price')"
                    type="number"
                    step="0.01"
                    min="0"
                    max="999999.99"
                    required
                    :placeholder="__('0.00')"
                    :error="$errors->first('price')"
                />

                <flux:input
                    name="stock_quantity"
                    :label="__('Stock Quantity')"
                    :value="old('stock_quantity')"
                    type="number"
                    min="0"
                    required
                    :placeholder="__('0')"
                    :error="$errors->first('stock_quantity')"
                />

                <div class="flex items-center gap-4">
                    <flux:button type="submit" variant="primary">
                        {{ __("Create Product") }}
                    </flux:button>
                    <flux:link
                        :href="route('products.index')"
                        variant="ghost"
                        wire:navigate
                    >
                        {{ __("Cancel") }}
                    </flux:link>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
