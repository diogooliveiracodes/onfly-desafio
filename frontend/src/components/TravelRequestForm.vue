<script setup lang="ts">
import { defineProps, defineEmits, ref } from 'vue'

const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits(['update:modelValue', 'submit'])

const newRequest = ref({
  destination: '',
  departure_date: '',
  return_date: ''
})

const close = () => {
  emit('update:modelValue', false)
}

const handleSubmit = () => {
  emit('submit', { ...newRequest.value })
  close()
}
</script>

<template>
  <v-dialog :model-value="props.modelValue" @update:model-value="emit('update:modelValue', $event)" max-width="500">
    <v-card>
      <v-card-title class="text-h6">Novo Pedido</v-card-title>
      <v-card-text>
        <v-text-field v-model="newRequest.destination" label="Destino" required />
        <v-text-field v-model="newRequest.departure_date" label="Data de Partida" type="date" required />
        <v-text-field v-model="newRequest.return_date" label="Data de Retorno" type="date" required />
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text @click="close">Cancelar</v-btn>
        <v-btn color="primary" @click="handleSubmit">Salvar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
