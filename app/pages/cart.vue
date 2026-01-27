<template>
    <UContainer class="py-5 flex flex-col gap-6">
        <UCard>
            <div class="flex items-center text-gray-500 text-sm font-normal">
                <div class="w-[46%]">Produk</div>
                <div class="text-center w-[15%]">Harga Satuan</div>
                <div class="text-center w-[15%]">Kuantitas</div>
                <div class="text-center w-[15%]">Total Harga</div>
                <div class="text-center w-[10%]">Aksi</div>
            </div>
        </UCard>

        <UCard>
            <template #header>
                <h2 class="text-sm text-black/85 font-medium">Shop name</h2>
            </template>
            <div class="grid grid-cols-1 divide-y">
                <div
                    v-for="i in 5"
                    :key="`product-${i}`"
                    class="flex items-center text-gray-500 text-sm font-normal py-5"
                >
                    <div class="w-[46%]">
                        <div class="flex gap-3 text-sm items-center">
                            <img
                                src="https://picsum.photos/1920/1080?random=1"
                                class="aspect-square w-20"
                            />
                            <p class="line-clamp-2 items-center text-black/85">
                                Kaos Game Over Pixelo - T-shirt / Tees Gamer PS
                                Nintendo Game Retro & Kaos 90an
                            </p>
                            <div class="w-40">
                                <p>Variasi:</p>
                                <p>Hitam, XXL</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center w-[15%]">
                        Rp{{ formatNumber(190000) }}
                    </div>
                    <div class="flex justify-center w-[15%]">
                        <BaseInputQuantity />
                    </div>
                    <div class="text-center w-[15%] text-primary">
                        Rp{{ formatNumber(190000) }}
                    </div>
                    <div class="text-center w-[10%]">
                        <UButton variant="link" color="black">Hapus</UButton>
                    </div>
                </div>
            </div>
        </UCard>
        <UCard
            :ui="{
                header: {
                    padding: paddingCheckoutFooter,
                },
                body: {
                    padding: paddingCheckoutFooter,
                },
                footer: {
                    padding: paddingCheckoutFooter,
                },
                divide: 'divide-dashed',
            }"
        >
            <template #header>
                <div class="flex justify-end gap-40">
                    <div class="flex gap-1 items-center font-medium">
                        <IconVoucher />
                        Voucher Syopo
                    </div>
                    <UButton
                        variant="link"
                        color="blue"
                        @click="openVoucher = true"
                    >
                        Gunakan/masukkan kode
                    </UButton>
                </div>
            </template>
            <template #default>
                <div class="flex justify-end">
                    <div class="flex items-center gap-5">
                        <UCheckbox>
                            <template #label>
                                <div class="flex gap-2">
                                    <IconCoin />
                                    <span class="font-medium">Koin Syopo</span>
                                </div>
                            </template>
                        </UCheckbox>
                        <span class="font-medium text-sm text-gray-500">
                            Saldo Koin Tidak Cukup
                        </span>
                        <div class="flex justify-end flex-1 min-w-48">
                            <span class="text-gray-300">-Rp0</span>
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex justify-end item">
                    <div class="flex gap-4">
                        <div>
                            <div class="flex items-center gap-1">
                                <span>Total ({{ 1 }} produk):</span>
                                <span class="text-primary font-normal text-2xl">
                                    Rp{{ formatNumber(145000) }}
                                </span>
                            </div>
                            <div
                                class="flex gap-6 text-sm font-normal justify-end"
                            >
                                <span>Hemat</span>
                                <span class="text-primary">104RB</span>
                            </div>
                        </div>
                        <UButton
                            class="px-9 min-w-52 justify-center"
                            @click="handleCheckout"
                            >Checkout</UButton
                        >
                    </div>
                </div>
            </template>
        </UCard>

        <ModalVoucher v-model="openVoucher" />
    </UContainer>
</template>

<script setup>
definePageMeta({
    layout: "auth",
    header: {
        showProfile: true,
        showSearch: true,
        title: "Keranjang Belanja",
    },
});

const openVoucher = ref(false);

const paddingCheckoutFooter = "sm:py-3 sm:px-7";

const router = useRouter();

function handleCheckout() {
    // Hit API
    router.push("/checkout");
}
</script>

<style scoped></style>
