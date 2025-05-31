<script lang="ts" setup>
import { ref } from 'vue'
import { QSpinner } from 'quasar'

const visible = ref(false)

document.addEventListener('inertia:start', () => {
    visible.value = true
})

document.addEventListener('inertia:finish', () => {
    visible.value = false
})
</script>

<template>
    <div v-if="visible" class="overlay-loader">
        <q-spinner color="orange-9" size="4rem" />
        <div class="text text-grey-10">{{ __('Loading...') }}'</div>
    </div>
</template>

<style scoped>
.overlay-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    z-index: 9999;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    font-size: 1.2rem;
    color: #ea580c;
}

.text {
    margin-top: 15px;
}
</style>
