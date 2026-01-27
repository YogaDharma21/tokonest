<template>
    <div class="flex flex-col min-h-screen relative text-slate-800">
        <LayoutsHeaderSeller />
        <aside class="bg-white p-4 fixed left-0 bottom-0 top-14 w-60">
            <LayoutsSidebar :items="sidebarItems" />
        </aside>
        <main class="flex-1 bg-black/10">
            <slot />
        </main>
    </div>
</template>

<script setup>
const sidebarItems = [
    {
        label: "Pesanan",
        icon: "i-heroicons:clipboard-document-list",
        to: "/seller/order",
    },
    {
        label: "Produk",
        icon: "i-heroicons:inbox-stack",
        children: [
            {
                label: "Produk Saya",
                to: "/seller/product",
            },
            {
                label: "Tambah Produk Baru",
                to: "/seller/product/add",
            },
        ],
    },
    {
        label: "Voucher",
        icon: "i-heroicons:receipt-percent",
        to: "/seller/voucher",
    },
    {
        label: "Keuangan",
        icon: "i-heroicons:banknotes",
        children: [
            {
                label: "Saldo Saya",
                to: "/seller/balance",
            },
        ],
    },
];
</script>
