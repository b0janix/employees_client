require('./bootstrap');

import {createApp} from "vue"

import App from "./components/App";

const app = createApp({
    components: {
        App
    }
}).mount("#app")




