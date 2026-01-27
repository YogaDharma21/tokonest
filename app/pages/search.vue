<template>
    <UContainer class="py-6 flex gap-4">
        <div class="flex flex-col gap-5 w-48">
            <div class="flex gap-2 items-center">
                <IconFilter />
                <h2 class="text-base font-bold text-black/80 uppercase">
                    Filter
                </h2>
            </div>
            <div class="flex flex-col gap-5">
                <h3 class="text-sm text-black/80 font-medium">Batas Harga</h3>
                <div class="flex gap-2 items-center">
                    <UInput type="number" placeholder="Rp MIN" />
                    <div class="h-px bg-gray-300 w-10" />
                    <UInput type="number" placeholder="Rp MAX" />
                </div>
                <UButton block class="py-2">TERAPKAN</UButton>
            </div>
            <hr />
            <div class="flex flex-col gap-5">
                <h3 class="text-sm text-black/80 font-medium">
                    Berdasarkan Kategori
                </h3>
                <div class="flex flex-col gap-2">
                    <UCheckbox label="Kategori 1" />
                    <UCheckbox label="Kategori 2" />
                    <UCheckbox label="Kategori 3" />
                    <UCheckbox label="Kategori 4" />
                </div>
            </div>
            <hr />
            <UButton block class="py-2">HAPUS SEMUA</UButton>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2 text-gray-600">
                <IconLamp />
                <p>
                    Hasil pencarian untuk '<span class="text-primary"
                        >Testing keyword</span
                    >'
                </p>
            </div>
            <div
                class="flex items-center gap-2 justify-between px-5 py-3 bg-black/5 mt-4"
            >
                <div class="flex gap-3 items-center">
                    <p class="text-gray-600 text-sm font-normal">Urutkan</p>
                    <UButton class="px-6">Termurah</UButton>
                    <UButton color="white" class="px-6">Termahal</UButton>
                </div>
                <div class="flex gap-5 items-center">
                    <p class="text-sm font-normal text-black/80">
                        <span class="text-primary">1</span>/17
                    </p>
                    <div>
                        <UButton
                            icon="i-heroicons:chevron-left-20-solid"
                            color="gray"
                            size="xs"
                            disabled
                        />
                        <UButton
                            icon="i-heroicons:chevron-right-20-solid"
                            color="gray"
                            size="xs"
                            class="bg-black/5"
                        />
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-5 gap-3 mt-3">
                <BaseProductCard
                    v-for="i in 10"
                    :key="`product-${i}`"
                    title="Kawabata Sepatu Sandal Kasual Slingback Mules"
                    price="10000"
                    image="https://s3-alpha-sig.figma.com/img/48e9/7de7/d13128b9ad75dda5a6e64cd6c03b5de2?Expires=1729468800&Key-Pair-Id=APKAQ4GOSFWCVNEHN3O4&Signature=I946mKhtIxrdSXQGZyOTw~qDLDwCmvBgsJnHd9w1kUtRnQwjUglcgZ89Y0UnWaLhdDPqeVUYTzntmRAfiLF0aXnwV7R~5LP2dsSq4pFz6aDJmfqAhzk3mrP9g2sp0W-Wm9FvUydrqE0PukRb8wlelWWinew6gPV0cMsYrjCJgXoiFWE3BY1xc7tN6ze~1eyag0Ig4mdG27FgS527RrU03778yo3ttMfHFAfk9UggrWHuFWAYp2XiLgFqr~3~6uboaVVhBTqpus61V0-0SYoR-5JPWF9BCG9c-1qfk494trUMNyfm3xUVgNoc1Mws4WS5Bo~0bhcAbjGZmVlq3Ntc9Q__"
                    sale="19"
                    :slug="`product-${i}`"
                />
            </div>
            <div class="flex justify-center mt-8">
                <BasePagination v-model="page" :total="items.length" />
            </div>
        </div>
    </UContainer>
</template>

<script setup>
const page = ref(1);
const items = ref(Array(55));
</script>

<style scoped></style>
