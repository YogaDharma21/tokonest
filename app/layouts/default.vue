<template>
    <div class="flex flex-col min-h-screen">
        <LayoutsHeaderOrange />
        <main class="flex-1 bg-blue-500">
            <slot />
        </main>
        <LayoutsFooter />
    </div>
</template>

<script setup></script>

<style scoped></style>
