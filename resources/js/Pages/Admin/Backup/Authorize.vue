<template>
    <div class="flex justify-center items-center bg-[#DB6939] h-screen">
        <div class="text-center">
            <h1 class="text-4xl text-white uppercase mb-16">Dropbox API</h1>
            <button @click="complete" class="border border-white py-4 px-6 rounded text-white flex gap-2" :disabled="processSetting" :class="{'opacity-25': processSetting}">
                <svg v-if="processSetting" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" class="w-[30px]" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                    <animateTransform
                                        attributeName="transform"
                                        attributeType="XML"
                                        type="rotate"
                                        dur="1s"
                                        from="0 50 50"
                                        to="360 50 50"
                                        repeatCount="indefinite" />
                                </path>
                            </svg>
                Complete setup</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Configuration",
    data(){
        return{
            processSetting: false,
        }
    },
    methods: {
        complete(){
            this.$inertia.get(route('admin.backup.authorize', {
                processing: this.processSetting = true
            }))
        }
    }

}
</script>

<style scoped>

</style>
