<template>
    <div class="border flex divide-x">
        <UButton
            color="white"
            icon="i-heroicons:minus"
            variant="none"
            @click="handleDecrease"
        />
        <UInput v-model="model" type="number" :padded="false" variant="none" />
        <UButton
            color="white"
            icon="i-heroicons:plus"
            variant="none"
            @click="handleIncrease"
        />
    </div>
</template>

<script setup>
const props = defineProps({
    min: {
        type: Number,
        default: 1,
    },
    max: {
        type: Number,
        default: 10,
    },
});

const model = defineModel({
    default: 1,
    type: Number,
});

function handleDecrease() {
    if (model.value > props.min) {
        model.value--;
    }
}

function handleIncrease() {
    if (model.value < props.max) {
        model.value++;
    }
}
</script>

<style scoped>
.input-quantity :deep(input) {
    height: 100%;
    text-align: center;
    width: 3.5rem;
}

.input-quantity :deep(input)::-webkit-outer-spin-button,
.input-quantity :deep(input)::-webkit-inner-spin-button {
    -webkit-appearance: none;
    appearance: none;
    margin: 0;
}

/* Firefox */
.input-quantity :deep(input)[type="number"] {
    -moz-appearance: textfield;
}
</style>
