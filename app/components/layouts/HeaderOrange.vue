<template>
    <header class="text-white flex flex-col h-32">
        <div class="bg-primary py-2">
            <u-container class="flex justify-end divide-x divide-gray-50/50">
                <u-button variant="link" color="white" class="p-0 px-3"
                    >Daftar</u-button
                >
                <u-button variant="link" color="white" class="p-0 px-3"
                    >Login</u-button
                >
            </u-container>
        </div>
        <div class="bg-linear-to-b from-primary to-[#ff6633] flex-1">
            <u-container
                class="flex justify-between h-full items-center gap-12"
            >
                <BaseLogo />
                <LayoutsSearchBar class="flex-1" />
                <u-button variant="link"><icon-cart /></u-button>
            </u-container>
        </div>
    </header>
</template>

<script setup></script>
