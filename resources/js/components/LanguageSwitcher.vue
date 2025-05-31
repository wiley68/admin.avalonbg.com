<script setup lang="ts">
import { ref, onMounted } from 'vue'

const localeOptions = [
    { label: 'Български', value: 'bg' },
    { label: 'English', value: 'en' },
]

const selectedLocale = ref('en')

onMounted(() => {
    const htmlLang = window.currentLocale
    selectedLocale.value = htmlLang || 'en'
})

function switchLocale(locale: string) {
    fetch(`/set-locale/${locale}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
    }).then(() => {
        window.location.reload()
    })
}
</script>

<template>
    <q-select v-model="selectedLocale" :options="localeOptions" @update:model-value="switchLocale" dense outlined
        emit-value map-options :label="__('Language')" style="width: 140px" />
</template>