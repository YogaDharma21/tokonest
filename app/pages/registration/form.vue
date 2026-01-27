<template>
    <section class="bg-white">
        <UContainer class="py-14">
            <div class="max-w-[500px] mx-auto">
                <div class="flex justify-between gap-2 items-center mb-16">
                    <template
                        v-for="(step, index) in registrationStep"
                        :key="step.key"
                    >
                        <div
                            class="step-item"
                            :class="[index <= stepActive && 'is-done']"
                        >
                            <div
                                class="flex flex-col items-center"
                                :class="[index <= stepActive && 'is-done']"
                            >
                                <div
                                    class="rounded-full px-3 py-1 ring-1 ring-inset flex items-center justify-center"
                                    :class="
                                        index <= stepActive
                                            ? 'bg-green-500 text-white ring-transparent'
                                            : 'ring-black/25 text-black/25'
                                    "
                                >
                                    {{ index + 1 }}
                                </div>
                                <p
                                    class="text-xs font-normal mt-1"
                                    :class="
                                        index <= stepActive
                                            ? 'text-green-500'
                                            : 'text-black/25'
                                    "
                                >
                                    {{ step.title }}
                                </p>
                            </div>
                        </div>
                        <IconStepArrow
                            v-if="index < registrationStep.length - 1"
                            class="mb-6"
                        />
                    </template>
                </div>
                <UCard class="auth-shadow">
                    <component
                        :is="registrationStep[stepActive].component"
                        @next="handleNext(registrationStep[stepActive].key)"
                        @back="handleBack(registrationStep[stepActive].key)"
                    />
                </UCard>
            </div>
        </UContainer>
    </section>
</template>

<script setup>
import { FormCompleted, FormOtp, FormPassword } from "#components";

definePageMeta({
    layout: "auth",
    header: {
        class: "custom-shadow sticky top-0 z-40",
        title: "Daftar",
    },
});

const router = useRouter();

const stepActive = ref(0);

const registrationStep = [
    {
        key: "otp",
        title: "Verifikasi email",
        component: FormOtp,
    },
    {
        key: "password",
        title: "Buat password",
        component: FormPassword,
    },
    {
        key: "completed",
        title: "Selesai",
        component: FormCompleted,
    },
];

function handleNext() {
    stepActive.value++;
}

function handleBack(stepKey) {
    if (stepKey === "otp") {
        return router.push("/registration");
    }
    stepActive.value--;
}
</script>

<style scoped>
.auth-shadow {
    box-shadow: 0px 3px 10px 0px #00000024;
}
</style>
