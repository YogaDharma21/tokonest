<template>
    <div
        class="bg-white flex items-center rounded-sm text-black"
        :class="{
            'p-1': padded,
            'border-2 border-primary': !padded,
        }"
    >
        <input
            placeholder="CEK: Flash Sale Rp17 Lokal!"
            class="outline-none pl-3 flex-1"
        />
        <UButton
            icon="i-heroicons:magnifying-glass"
            class="px-6"
            v-bind="attribute"
        />
    </div>
</template>

<script setup>
const props = defineProps({
    padded: {
        type: Boolean,
        default: true,
    },
});

const attribute = computed(() => {
    if (!props.padded) {
        return {
            ui: {
                rounded: "rounded-none",
            },
        };
    }
    return {};
});
</script>

<style scoped></style>
