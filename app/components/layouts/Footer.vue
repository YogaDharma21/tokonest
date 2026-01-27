<template>
    <footer class="border-t-4 border-primary bg-gray-50">
        <UContainer class="flex flex-col">
            <div class="grid grid-cols-5 pt-11 pb-8 border-b">
                <div>
                    <h3>Layanan Pelanggan</h3>
                    <nav>
                        <ul>
                            <li
                                v-for="(item, index) in layananPengguna"
                                :key="`${item}-${index}`"
                            >
                                <UButton
                                    variant="link"
                                    :padded="false"
                                    size="xs"
                                    class="text-xs text-black/65 font-normal"
                                >
                                    {{ item }}
                                </UButton>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div>
                    <h3>Jelajahi Syopo</h3>
                    <nav>
                        <ul>
                            <li
                                v-for="(item, index) in jelajahi"
                                :key="`${item}-${index}`"
                            >
                                <UButton
                                    variant="link"
                                    :padded="false"
                                    size="xs"
                                    class="text-xs text-black/65 font-normal"
                                >
                                    {{ item }}
                                </UButton>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div>
                    <div>
                        <h3>Pembayaran</h3>
                        <div class="flex flex-wrap gap-2">
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/seabank.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/bca.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/bni.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/bri.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/bsi.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/cimb.png"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3>Pengiriman</h3>
                        <div class="flex flex-wrap gap-2">
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/spx.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/jnt.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/jne.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/ninja.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/sicepat.png"
                                />
                            </div>
                            <div
                                class="w-15 h-7.5 bg-white rounded-sm p-1 shadow"
                            >
                                <img
                                    class="w-full"
                                    src="~/assets/images/gosend.png"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <h3>Ikuti Kami</h3>
                        <nav>
                            <ul>
                                <li>
                                    <UButton
                                        variant="link"
                                        :padded="false"
                                        size="xs"
                                        class="text-xs text-black/65 font-normal"
                                    >
                                        <template #leading>
                                            <img
                                                class="w-full"
                                                src="~/assets/images/facebook.png"
                                            />
                                        </template>
                                        Facebook
                                    </UButton>
                                </li>
                                <li>
                                    <UButton
                                        variant="link"
                                        :padded="false"
                                        size="xs"
                                        class="text-xs text-black/65 font-normal"
                                    >
                                        <template #leading>
                                            <img
                                                class="w-full"
                                                src="~/assets/images/instagram.png"
                                            />
                                        </template>
                                        Instagram
                                    </UButton>
                                </li>
                                <li>
                                    <UButton
                                        variant="link"
                                        :padded="false"
                                        size="xs"
                                        class="text-xs text-black/65 font-normal"
                                    >
                                        <template #leading>
                                            <img
                                                class="w-full"
                                                src="~/assets/images/twitter.png"
                                            />
                                        </template>
                                        Twitter
                                    </UButton>
                                </li>
                                <li>
                                    <UButton
                                        variant="link"
                                        :padded="false"
                                        size="xs"
                                        class="text-xs text-black/65 font-normal"
                                    >
                                        <template #leading>
                                            <img
                                                class="w-full"
                                                src="~/assets/images/linkedin.png"
                                            />
                                        </template>
                                        Linkedin
                                    </UButton>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="mt-6">
                        <h3>Keamanan</h3>
                        <div class="w-28 h-13 bg-white rounded-sm p-1 shadow">
                            <img
                                class="w-full"
                                src="~/assets/images/certified.png"
                            />
                        </div>
                    </div>
                </div>
                <div>
                    <h3>Download Aplikasi Syopo</h3>
                    <div class="flex gap-3">
                        <img class="w-full" src="~/assets/images/qrcode.png" />
                        <div class="flex flex-col gap-2">
                            <img
                                class="w-full"
                                src="~/assets/images/appstore.png"
                            />
                            <img
                                class="w-full"
                                src="~/assets/images/playstore.png"
                            />
                            <img
                                class="w-full"
                                src="~/assets/images/appgalery.png"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-10 flex justify-between">
                <p class="text-xs text-black/65 font-normal">
                    © Syopo 2024. Hak Cipta Dilindungi
                </p>
                <div class="flex gap-1 items-center">
                    <span class="text-xs text-black/65 font-normal"
                        >Negara:</span
                    >
                    <div class="flex divide-x divide-black/20">
                        <UButton
                            v-for="item in country"
                            :key="item"
                            variant="link"
                            class="footer-text px-1.5"
                            :padded="false"
                        >
                            {{ item }}
                        </UButton>
                    </div>
                </div>
            </div>
        </UContainer>
    </footer>
</template>

<script setup>
const layananPengguna = [
    "Bantuan",
    "Metode Pembayaran",
    "SyopoPay",
    "Koin Syopo",
    "Lacak Pesanan Pembeli",
    "Lacak Pengiriman Penjual",
    "Gratis Ongkir",
    "Pengembalian Barang & Dana",
    "Garansi Syopo",
    "Hubungi Kami",
];

const jelajahi = [
    "Tentang Kami",
    "Karir",
    "Kebijakan Syopo",
    "Kebijakan Privasi",
    "Blog",
    "Syopo Mall",
    "Seller Center",
    "Flash Sale",
    "Kontak Media",
    "Syopo Affiliate",
];

const country = [
    "Singapura",
    "Indonesia",
    "Thailand",
    "Malaysia",
    "Vietnam",
    "Filipina",
    "Brazil",
    "Mexico",
    "Colombia",
    "Chile",
    "Taiwan",
];
</script>

<style scoped></style>
