<template>
    <div class="flex justify-between items-center mt-6">
        <div class=""></div>
        <div class="flex items-center" v-if="links.length > 3">
            <div v-for="(link, p) in links">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-2.5 mt-1 text-sm leading-4 text-gray-400 border rounded"
                     v-html="link.label" />
                <Link v-else
                              class="mr-1 mb-1 px-4 py-2 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                              :class="{ 'disabled bg-blue-700 text-white hover:bg-blue-700': link.active }" :href="link.url" v-html="link.label" />

            </div>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3';
export default {
    name: "Pagination",
    components:{
      Link
    },
    props: {
        links: Array
    }
}
</script>

<style scoped>

</style>
