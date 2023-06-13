<script setup>

const emit = defineEmits(['clickDeleteMsg'])

/**
 * Hide the notification message.
 * @returns {void}
 */
function buttonHide() {
  emit('clickDeleteMsg')
}

</script>

<template>
    <div class="notification">
        <button class="delete" @click="buttonHide"/>
        <slot/>
    </div>
</template>
