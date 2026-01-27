<template>
    <UContainer class="py-5 text-black/85 text-sm">
        <UCard>
            <template #header>
                <div class="flex items-center gap-4">
                    <UButton
                        :padded="false"
                        icon="i-heroicons:arrow-left"
                        variant="link"
                        color="black"
                    />
                    <p class="text-xl">Pembayaran</p>
                </div>
            </template>
            <template #default>
                <div class="flex justify-center">
                    <div class="max-w-125 w-full divide-y">
                        <div class="py-5 flex justify-between">
                            <span class="text-base font-normal"
                                >Total Pembayaran</span
                            >
                            <span class="text-base font-medium text-primary"
                                >Rp{{ formatNumber(100000) }}</span
                            >
                        </div>
                        <div class="payment-item justify-between">
                            <span class="text-base font-normal"
                                >Bayar Dalam</span
                            >
                            <div class="text-right">
                                <p class="text-primary text-base font-medium">
                                    23 jam 59 menit 50 detik
                                </p>
                                <p class="text-black/55 font-normal">
                                    Jatuh tempo 11 Ags 2024, 16:26
                                </p>
                            </div>
                        </div>
                        <div class="py-5 flex flex-col gap-5">
                            <div class="flex gap-2">
                                <img
                                    :src="paymentFrom.image"
                                    class="w-6 object-contain"
                                />
                                <p>{{ paymentFrom.label }}</p>
                            </div>
                            <div v-if="paymentSelected === 'bni_va'">
                                <p>No. Rekening</p>
                                <div class="flex gap-4 mt-1 items-center">
                                    <p class="text-primary text-xl">
                                        XXXX XXXX XXXX
                                    </p>
                                    <UButton
                                        variant="link"
                                        color="teal"
                                        :padded="false"
                                        >SALIN</UButton
                                    >
                                </div>
                            </div>
                            <img
                                v-if="paymentSelected === 'qris'"
                                src="/images/qris-example.png"
                                class="w-60 mx-auto"
                            />
                            <p class="text-teal-500">
                                Proses verifikasi kurang dari 10 menit setelah
                                pembayaran berhasil
                            </p>
                            <p>
                                {{ message[paymentSelected].description }}
                            </p>
                            <p>{{ message[paymentSelected].caption }}</p>
                            <UButton
                                block
                                class="max-w-72 mx-auto mt-7"
                                @click="handleCheckPayment"
                                >OK</UButton
                            >
                        </div>
                    </div>
                </div>
            </template>
        </UCard>

        <UModal v-model="isSuccess">
            <UCard
                :ui="{
                    rounded: 'rounded',
                }"
            >
                <p class="text-lg">Pembayaran Berhasil</p>
                <p class="text-black/55 text-s mt-5">
                    Silakan cek notifikasi untuk mengetahui status pengiriman.
                </p>
                <div class="flex justify-end mt-1">
                    <UButton
                        block
                        class="max-w-44 mt-10"
                        @click="isSuccess = false"
                        >OK</UButton
                    >
                </div>
            </UCard>
        </UModal>
    </UContainer>
</template>

<script setup>
definePageMeta({
    header: {
        showProfile: false,
        showSearch: false,
        showCart: false,
    },
});

const isSuccess = ref(false);
const paymentSelected = "bni_va";

const paymentFrom = computed(() =>
    [
        {
            value: "bni_va",
            label: "Bank BNI",
            image: "/images/logo-bni.webp",
        },
        {
            value: "qris",
            label: "QRIS",
            image: "/images/qris.png",
        },
    ].find((item) => item.value === paymentSelected),
);

const message = {
    bni_va: {
        description:
            "Bayar pesanan ke Virtual Account di atas sebelum membuat pesanan kembali dengan Virtual Account agar nomor tetap sama.",
        caption: "Hanya menerima dari Bank BNI",
    },
    qris: {
        description:
            "Bayar pesanan ke QRIS di atas sebelum membuat pesanan kembali dengan QRIS agar nomor tetap sama.",
        caption: "Menerima dari semua Bank",
    },
};

function handleCheckPayment() {
    // TODO: hit api
    isSuccess.value = true;
}
</script>

<style scoped></style>
