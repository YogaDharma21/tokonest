<template>
  <UModal v-model="isOpen" prevent-close>
    <UCard class="text-black/85 text-sm">
      <p class="text-xl font-normal">Nilai Produk</p>
      <ModalReviewItem
        v-for="(item, index) in order?.items"
        :key="item.uuid"
        v-model="form[index]"
        :item="item"
      />

      <div class="flex justify-end gap-2 pt-6">
        <UButton
          class="min-w-36 justify-center"
          variant="link"
          color="gray"
          :disabled="status === 'pending'"
          @click="onClose"
        >
          Nanti Saja
        </UButton>
        <UButton
          class="min-w-36 justify-center"
          :loading="status === 'pending'"
          @click="onSubmit"
        >
          Ok
        </UButton>
      </div>
    </UCard>
  </UModal>
</template>

<script setup>
import useVuelidate from "@vuelidate/core";
const session = useSession();
const toast = useToast();

const isOpen = defineModel("open", {
  type: Boolean,
  default: false,
});
const order = ref({});
const form = ref([
  {
    uuid: "",
    rating: 0,
    courierRating: 0,
    description: "",
    photo: [],
    video: [],
    showUsername: false,
  },
]);
const v$ = useVuelidate({}, form, {
  $scope: "review",
});
function setDefaultData(newOrder) {
  if (newOrder && typeof newOrder === "object") {
    order.value = newOrder;

    form.value = order.value.items?.map((item) => ({
      uuid: item.uuid,
      rating: 0,
      courierRating: 0,
      description: "",
      photo: [],
      video: [],
      showUsername: false,
    }));
  }
}

function onClose() {
  form.value = [
    {
      uuid: "",
      rating: 0,
      courierRating: 0,
      description: "",
      photo: [],
      video: [],
      showUsername: false,
    },
  ];
  v$.value.$reset();
  isOpen.value = false;
}

// const { execute, status } = useSubmit("/server/api/order/review/add", {
//   onResponse({ response }) {
//     if (response.ok) {
//       refreshNuxtData("order-customer");
//       onClose();
//     }
//   },
// });

const { execute, status, error } = useAsyncData(
  () => Promise.all(form.value.map((item) => submitReview(item))),
  {
    immediate: false,
  }
);

async function onSubmit() {
  const isValid = await v$.value.$validate();
  if (!isValid) {
    toast.add({
      color: "red",
      title: v$.value.$errors?.[0]?.$message?.replace("value", "rating"),
    });
    return;
  }

  //   TODO: HIT API
  // const formData = new FormData();
  // formData.append("order_item_uuid", order.value.items?.[0]?.uuid);
  // formData.append("star_seller", formRating.value.rating);
  // formData.append("star_courier", formRating.value.courierRating);
  // formData.append("description", formRating.value.description);
  // formRating.value.photo.forEach((item) => {
  //   formData.append("attachments[]", item);
  // });
  // formRating.value.video.forEach((item) => {
  //   formData.append("attachments[]", item);
  // });
  // formData.append("show_username", formRating.value.showUsername ? 1 : 0);

  // execute(formData);

  await execute();
  if (error.value) return;
  emit('success')
  onClose();
}

async function submitReview(item) {
  const formData = new FormData();
  formData.append("order_item_uuid", item?.uuid);
  formData.append("star_seller", item.rating);
  formData.append("star_courier", item.courierRating);
  formData.append("description", item.description);
  item.photo.forEach((item) => {
    formData.append("attachments[]", item);
  });
  item.video.forEach((item) => {
    formData.append("attachments[]", item);
  });
  formData.append("show_username", item.showUsername ? 1 : 0);

  return $fetch("/server/api/order/review/add", {
    method: "POST",
    body: formData,
    headers: {
      Authorization: `Bearer ${session.token}`,
    },
    onResponseError({ response }) {
      if (response._data.meta?.messages?.[0]) {
        toast.add({
          color: "red",
          title: response._data.meta.messages[0],
        });
      }
    },
    retry: false,
  });
}

defineExpose({
  setDefaultData,
});
</script>

<style lang="scss" scoped></style>
