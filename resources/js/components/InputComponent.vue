<template>
    <div class="single-input-container">

        <label :for="input_name" class="input-label" v-if="show_label">{{transform}}</label>
        <input :type="input_type"
               :name="input_name"
               :value="input_text"
               :class="[
                   type === 'submit' ? 'button-general' : '',
                   type === 'submit'
                   && input_color.length > 0
                   && input_color === 'blue' ? 'blue-button' : '',
                   type === 'submit'
                   && input_color.length > 0
                   && input_color === 'red' ? 'red-button' : '',
                   ]"
               :readonly="read_only"
               :style="{
                cursor: 'pointer',
                width: '100%',
                padding: '10px'
                }"
        >


    </div>
</template>

<script>
export default {
    name: "InputComponent",
    props: ['type','name', 'text', 'color', 'readonly'],
    data() {
      return {
          input_type: this.type,
          input_name: this.name,
          input_text: this.text,
          input_color: this.color,
          show_label: false,
          read_only: false
      }
    },
    computed: {
        transform() {
            let name = this.input_name;
            name = name.replaceAll(/_/g, ' ')
            return name.charAt(0).toUpperCase() + name.slice(1)
        }
    },
    methods: {
        isReadOnly() {
            this.read_only = this.readonly === '1'
        },
        checkType() {
            let arr = ['submit', 'hidden'];
            this.show_label = !arr.includes(this.type);
        }
    },
    mounted() {
        this.checkType();
        this.isReadOnly();
    }

}
</script>

<style scoped>
    .single-input-container {
        margin-top: 10px;
    }
    .input-label {
        margin-right: 20px;
    }
    .blue-button {
        background-color: cornflowerblue;
    }
    .red-button {
        background-color: red;
    }
    .button-general {
        padding: 5px;
        border-radius: 3px;
        color: white;
        border: 1px solid white;
        width: 100px;
    }
</style>
