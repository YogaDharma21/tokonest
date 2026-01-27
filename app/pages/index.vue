<template>
    <div class="flex flex-col gap-6 pb-6">
        <section class="bg-white py-7">
            <UContainer>
                <FeatureHomepageCarousel :items="items" />
            </UContainer>
        </section>
        <section class="category-section">
            <UContainer>
                <div class="bg-white">
                    <div class="p-5 border-b border-black/5">
                        <h2
                            class="text-base text-black/55 font-medium uppercase"
                        >
                            Kategori
                        </h2>
                    </div>
                    <div class="grid grid-cols-10">
                        <FeatureHomepageCategoryItem
                            v-for="i in 20"
                            :key="`cat-${i}`"
                            title="Elektronik"
                            image="https://picsum.photos/1920/1080?random=7"
                        />
                    </div>
                </div>
            </UContainer>
        </section>
        <section class="product-section">
            <UContainer>
                <div class="bg-white border-b-4 border-primary p-5">
                    <h2
                        class="text-base text-primary font-medium text-center uppercase"
                    >
                        Rekomendasi
                    </h2>
                </div>
                <div class="grid grid-cols-6 gap-3 mt-3">
                    <BaseProductCard
                        v-for="i in 36"
                        :key="`product-${i}`"
                        title="Kawabata Sepatu Sandal Kasual Slingback Mules"
                        price="10000"
                        image="https://picsum.photos/1920/1080?random=8"
                        :slug="`product-${i}`"
                    />
                </div>
            </UContainer>
        </section>

        <UContainer>
            <UButton color="white" class="font-normal px-28">
                Login untuk Lihat Lainnya
            </UButton>
        </UContainer>
    </div>
</template>

<script setup>
const items = [
    "https://picsum.photos/1920/1080?random=1",
    "https://picsum.photos/1920/1080?random=2",
    "https://picsum.photos/1920/1080?random=3",
    "https://picsum.photos/1920/1080?random=4",
    "https://picsum.photos/1920/1080?random=5",
    "https://picsum.photos/1920/1080?random=6",
];
</script>

<style scoped></style>
