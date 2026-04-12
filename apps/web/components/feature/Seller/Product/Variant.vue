<template>
  <div class="p-4 bg-gray-100 rounded-sm">
    <div class="flex justify-between items-center">
      <div>
        <UFormGroup
          v-if="isEditVariantName"
          :error="v$.name.$errors?.[0]?.$message"
        >
          <UInput
            v-model="variantName"
            autofocus
            placeholder="Variant Name"
            @blur="handleSaveVariantName"
          />
        </UFormGroup>
        <div v-else class="flex gap-1">
          <span class="text-sm">
            {{ variantName }}
            <span class="text-black/30 text-xs">(Atur Sendiri)</span></span
          >
          <UButton
            icon="i-heroicons:pencil-square"
            variant="link"
            color="blue"
            :padded="false"
            @click="isEditVariantName = true"
          />
        </div>
      </div>
      <UButton
        variant="ghost"
        icon="i-heroicons:x-mark"
        size="xs"
        color="gray"
        @click="emit('delete')"
      />
    </div>
    <hr class="my-4"/>
    <div class="grid grid-cols-2 gap-4 mt-4">
      <UFormGroup
        v-for="(varValue, index) in variantValue"
        :key="`variant-${index}`"
        :error="v$.value.$each.$response.$errors?.[index]?.name?.[0]?.$message"
      >
        <div class="flex gap-2">
          <UInput v-model="varValue.name" class="flex-1" />
          <UButton
            v-if="variantValue.length > 1"
            icon="i-heroicons:trash"
            :padded="false"
            size="xs"
            variant="link"
            color="gray"
            @click="handleRemoveVariantValue(index)"
          />
        </div>
      </UFormGroup>
    </div>
    <UButton
      icon="i-heroicons:plus"
      label="Tambah"
      size="xs"
      color="white"
      class="mt-4"
      @click="handleAddVariantValue"
    />
  </div>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
import { helpers, minValue, numeric, required } from "@vuelidate/validators";

const emit = defineEmits(["delete"]);
const variantName = defineModel({
  type: String,
  default: "",
});

const variantValue = defineModel("value", {
  type: Array,
  default: () => [],
});

const rules = {
  name: { required },
  value: {
    $each: helpers.forEach({
      name: { required, },
    }),
  },
};
const v$ = useVuelidate(
  rules,
  computed(() => ({ name: variantName.value, value: variantValue.value })),
  {
    $autoDirty: true,
    $scope: "product",
  }
);

const isEditVariantName = ref(true);

function handleSaveVariantName() {
  if (variantName.value) {
    isEditVariantName.value = false;
  }
}

function handleRemoveVariantValue(index) {
  variantValue.value.splice(index, 1);
}

function handleAddVariantValue() {
  variantValue.value.push({ name: "" });
}
</script>

<style lang="scss" scoped></style>
