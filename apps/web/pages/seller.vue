<template>
  <NuxtPage />
</template>

<script setup>
definePageMeta({
  layout: "seller",
  middleware: ["must-auth"],
});
</script>
