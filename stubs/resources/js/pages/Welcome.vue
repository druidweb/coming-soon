<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import type { SharedData } from '@/types';

const page = usePage<SharedData>();
const appName = computed(() => page.props.name);
</script>

<template>
  <Head title="Coming Soon" />
  <div class="welcome-page flex min-h-screen flex-col items-center justify-center p-6 text-white">
    <div class="w-full max-w-2xl text-center">
      <!-- Logo/Brand Area -->
      <div class="mb-8">
        <img src="/storage/img/logo.svg" alt="Logo" class="mx-auto h-auto w-48" />
      </div>
      <!-- Main Message -->
      <div class="mb-12">
        <h2 class="mb-4 text-xl font-medium text-slate-400 md:text-2xl">{{ __('Coming Soon') }}</h2>
        <h1 class="mb-4 text-2xl font-semibold text-white md:text-4xl">{{ appName }}</h1>
        <p class="text-lg text-slate-400 md:text-xl">
          {{ __("We're working hard to bring you something amazing. Stay tuned!") }}
        </p>
      </div>

      <!-- Optional Contact Info -->
      <div class="text-sm text-slate-400">
        <p>{{ __('For inquiries, please contact us at:') }}</p>
        <a href="mailto:hello@jetstreamlabs.com" class="font-medium text-orange-400 hover:text-orange-600">
          {{ __('hello@jetstreamlabs.com') }}
        </a>
      </div>
    </div>
  </div>
</template>
