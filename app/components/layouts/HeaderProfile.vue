<template>
    <div class="bg-primary py-2 text-white">
        <UContainer class="flex justify-end divide-x divide-gray-50/50">
            <UButton
                variant="link"
                color="white"
                :padded="false"
                class="px-3"
                to="/registration"
            >
                Daftar
            </UButton>
            <UButton
                variant="link"
                color="white"
                :padded="false"
                class="px-3"
                to="/login"
            >
                Login
            </UButton>
        </UContainer>
    </div>
</template>

<script setup></script>

<style scoped></style>
