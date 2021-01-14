<template>
    <div class="content-container">
        <form :action="redirect" method="post">
            <InputComponent
                type="submit"
                name="submit"
                :text="text"
                color="blue"
            >
            </InputComponent>

            <InputComponent
                type="hidden"
                name="_token"
                :text="csrf"
            >
            </InputComponent>
        </form>
        <form v-if="delete_tokens === '1'" action="/truncateTokens" method="post">
            <InputComponent
                type="submit"
                name="submit"
                text="Delete all tokens"
                color="red"
            >
            </InputComponent>

            <InputComponent
                type="hidden"
                name="_token"
                :text="csrf"
            >
            </InputComponent>
        </form>
    </div>

</template>

<script>
import InputComponent from "./InputComponent";
export default {
    name: "App",
    props: [
        'redirect',
        'text',
        'csrf',
        'delete_tokens'

    ],
    data() {
      return {

      }
    },
    components: {
        InputComponent
    },
    computed: {

    },
    methods: {

    },
    mounted() {
        console.log(this.delete_tokens);
    }
}
</script>

<style scoped>
.content-container {
    margin-top: 50px;
    display: flex;
    flex-flow: row;
    justify-content: center;
}
</style>
