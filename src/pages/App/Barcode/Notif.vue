<script setup>

const emit = defineEmits(['clickDeleteMsg'])

/**
 * Hide the notification message
 */
function buttonHide() {
    emit('clickDeleteMsg')
}

</script>

<template>
    <div class="notification">
        <button class="delete" @click="buttonHide"></button>
        <slot></slot>
    </div>
</template>