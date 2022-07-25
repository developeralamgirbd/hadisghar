<template>
    <div class="menubar">
                <ul class="px-2 py-4 mt-4">
                    <li class="mb-8">
                        <Link :href="route('admin.dashboard')" :class="{'text-rose-500': $page.component === 'Admin/Dashboard'}" class="text-white hover:text-white transition duration-200 text-xl lg:text-sm flex items-center gap-1">
                           <fa icon="gauge-high" class="font-light text-sm" /> Dashboard </Link>
                    </li>

                    <!--------------------------------------------------------------------------
                  | Posts Route Start
                  ---------------------------------------------------------------------------->
                    <div v-if="permissionArr.includes('view posts') || permissionArr.includes('create post') || permissionArr.includes('view categories')">
                        <li class="mb-8" >
                            <a href="#" class="text-gray-400" >
                                <div class="mb-3 w-full flex items-center justify-between text-white transition duration-200" @click="postSubmenuOpen = !postSubmenuOpen" :class="{'text-white': $page.component.startsWith('Admin/Posts')}">
                                    <p class="flex items-center gap-1 text-xl lg:text-sm font-light"> <fa icon="pen-to-square" class="text-sm"/>Posts</p>
                                    <p class="font-light transition duration-300 text-[10px]">
                                        <fa icon="chevron-left" :class="postSubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="postSubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>
                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="submenu text-lg lg:text-sm font-light" v-if="postSubmenuOpen || $page.component.startsWith('Admin/Posts')" :class="{'block': $page.component.startsWith('Admin/Posts')}">
                                        <li v-if="permissionArr.includes('view posts')" class="mb-2">
                                            <Link :href="route('admin.post.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Posts/Manage' || $page.component === 'Admin/Posts/Trashed'}"> All Posts</Link>
                                        </li>
                                        <li v-if="permissionArr.includes('create post')" class="mb-2">
                                            <Link :href="route('admin.post.create')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Posts/Create'}">Add New</Link>
                                        </li>
                                        <li v-if="permissionArr.includes('view categories')" class="mb-2">
                                            <Link :href="route('admin.category.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Posts/Category' || $page.component === 'Admin/Posts/CategoryTrashed'}">Categories</Link>
                                        </li>
                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | Posts Route End
                    ---------------------------------------------------------------------------->

                    <!--------------------------------------------------------------------------
                    | Users Route Start
                    ---------------------------------------------------------------------------->
                    <div v-if="permissionArr.includes('view users') || permissionArr.includes('create user')">
                        <li class="mb-8" >
                            <a href="#" class="text-white" >
                                <div class="w-full flex items-center justify-between text-white transition duration-200" @click="userSubmenuOpen = !userSubmenuOpen" :class="{'text-white': $page.component.startsWith('Admin/Users')}">
                                    <p class="flex items-center gap-1 text-xl lg:text-sm font-light"> <fa icon="users" class="text-sm"/>Users</p>
                                    <p class="font-light text-[10px] transition duration-300">
                                        <fa icon="chevron-left" :class="userSubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="userSubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>
                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="submenu mt-4 text-lg lg:text-sm font-light" v-if="userSubmenuOpen || $page.component.startsWith('Admin/Users')" :class="{'block': $page.component.startsWith('Admin/Users')}">
                                        <li v-if="permissionArr.includes('view users')" class="mb-2">
                                            <Link :href="route('admin.user.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-white text-rose-500': $page.component === 'Admin/Users/Manage'}"> All Users</Link>
                                        </li>
                                        <li v-if="permissionArr.includes('create user')" class="mb-2">
                                            <Link :href="route('admin.user.create')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-white text-rose-500': $page.component === 'Admin/Users/Create'}">Add New</Link>
                                        </li>
                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | Users Route End
                    ---------------------------------------------------------------------------->

                    <!--------------------------------------------------------------------------
                   | Role Route Star
                   ---------------------------------------------------------------------------->
                    <div v-if="permissionArr.includes('view roles') || permissionArr.includes('create role')">
                        <li class="mb-8" >
                            <a href="#" class="text-gray-400" >
                                <div class="w-full flex items-center justify-between text-white transition duration-200" @click="membersSubmenuOpen = !membersSubmenuOpen" :class="{'text-white': $page.component.startsWith('Admin/Members') || $page.component.startsWith('Admin/Permissions')}">
                                    <p class="flex items-center gap-1 text-xl lg:text-sm font-light"> <fa icon="users-gear" class="text-sm"/>User Roles</p>
                                    <p class="font-light text-[10px] transition duration-300">
                                        <fa icon="chevron-left" :class="membersSubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="membersSubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>

                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="mt-4 submenu text-lg lg:text-sm font-light" v-if="membersSubmenuOpen || $page.component.startsWith('Admin/Members') || $page.component.startsWith('Admin/Permissions')" :class="{'block': $page.component.startsWith('Admin/Members') || $page.component.startsWith('Admin/Permissions')}">
                                        <li v-if="permissionArr.includes('view roles')" class="mb-2">
                                            <Link :href="route('admin.role.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Members/Manage'}"> Roles</Link>
                                        </li>
                                        <li v-if="permissionArr.includes('create role')" class="mb-2">
                                            <Link :href="route('admin.role.create')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Members/Create'}"> Add New Role</Link>
                                        </li>

                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | Role Link End
                    ---------------------------------------------------------------------------->

                    <!--------------------------------------------------------------------------
                | Security Route Star
                ---------------------------------------------------------------------------->
                    <div v-if="$page.props.user_role_check.toLowerCase() === 'admin'">
                        <li class="mb-8" >
                            <a href="#" class="text-gray-400" >
                                <div class="w-full flex items-center justify-between text-white transition duration-200" @click="securitySubmenuOpen = !securitySubmenuOpen" :class="{'text-white': $page.component.startsWith('Admin/Security')}">
                                    <p class="font-light text-xl lg:text-sm flex items-center gap-1"> <fa icon="shield-halved" class="text-sm"/>Security</p>
                                    <p class="font-light text-[10px] transition duration-300">
                                        <fa icon="chevron-left" :class="securitySubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="securitySubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>
                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="mt-4 submenu text-lg lg:text-sm font-light" v-if="securitySubmenuOpen
                                    || $page.component.startsWith('Admin/Security')"
                                        :class="{'block': $page.component.startsWith('Admin/Security')}">
                                        <li class="mb-2">
                                            <Link :href="route('admin.limit.login.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Security/LimitLoginAttempt'}"> Limit Login Attempts</Link>
                                        </li>
                                        <li class="mb-2">
                                            <Link :href="route('admin.brute.force.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Security/BruteForce'}"> Brute Force</Link>
                                        </li>
                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | Security Route End
                    ---------------------------------------------------------------------------->


                    <!--------------------------------------------------------------------------
                    | DB Backup Route Start
                    ---------------------------------------------------------------------------->
                    <div v-if="$page.props.user_role_check.toLowerCase() === 'admin'">
                        <li class="mb-8" >
                            <a href="#" class="text-gray-400 " >
                                <div class="w-full flex items-center justify-between  text-white transition duration-200" @click="backupSubmenuOpen = !backupSubmenuOpen">
                                    <p class="font-light flex items-center gap-1 text-xl lg:text-sm"> <fa icon="fa-database" class="text-sm"></fa>Backup</p>
                                    <p class="font-light text-[10px] transition duration-300">
                                        <fa icon="chevron-left" :class="backupSubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="backupSubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>
                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="mt-4 submenu text-lg lg:text-sm font-light" v-if="backupSubmenuOpen
                                    || $page.component.startsWith('Admin/Backup')"
                                        :class="{'block': $page.component.startsWith('Admin/Backup')}">
                                        <li class="mb-2">
                                            <Link :href="route('admin.backup.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Backup/Manage' || $page.component === 'Admin/Backup/Trash'}">Backup Now</Link>
                                        </li>
                                        <li class="mb-2">
                                            <Link :href="route('admin.backup.settings')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Backup/Settings'}">Settings</Link>
                                        </li>
                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | DB Backup Route End
                    ---------------------------------------------------------------------------->

                    <!--------------------------------------------------------------------------
                  | Schedule Route Start
                  ---------------------------------------------------------------------------->
                    <div v-if="$page.props.user_role_check.toLowerCase() === 'admin'">
                        <li class="mb-8" >
                            <a href="#" class="text-gray-400 " >
                                <div class="w-full flex items-center justify-between  text-white transition duration-200" @click="scheduleSubmenuOpen = !scheduleSubmenuOpen">
                                    <p class="font-light flex items-center gap-1 text-xl lg:text-sm"> <fa icon="fa-calendar-days" class="text-sm"></fa>Schedule </p>
                                    <p class="font-light text-[10px] transition duration-300">
                                        <fa icon="chevron-left" :class="scheduleSubmenuOpen ? 'hidden' : 'block'"/>
                                        <fa icon="chevron-down" :class="scheduleSubmenuOpen ? 'block' : 'hidden'"/>
                                    </p>
                                </div>
                                <!-- Submenu -->
                                <Transition name="slide-fade">
                                    <ul class="mt-4 submenu text-lg lg:text-sm font-light" v-if="scheduleSubmenuOpen
                                    || $page.component.startsWith('Admin/Schedule')"
                                        :class="{'block': $page.component.startsWith('Admin/Schedule')}">
                                        <li class="mb-2">
                                            <Link :href="route('admin.schedule.index')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Schedule/Calender'}">Calender</Link>
                                        </li>
                                        <li class="mb-2">
                                            <Link :href="route('admin.schedule.settings')" class="block hover:bg-[#F0F0F0] text-white hover:text-rose-500 rounded pl-6 py-1 transition duration-200" :class="{'bg-[#F0F0F0] text-rose-500': $page.component === 'Admin/Schedule/Settings'}">Settings</Link>
                                        </li>
                                    </ul>
                                </Transition>
                            </a>
                        </li>
                    </div>
                    <!--------------------------------------------------------------------------
                    | Schedule Route End
                    ---------------------------------------------------------------------------->
                    <!--------------------------------------------------------------------------
                     | Mail Setting Route Start
                     ---------------------------------------------------------------------------->
                    <li v-if="$page.props.user_role_check.toLowerCase() === 'admin'" class="mb-8">
                        <Link :href="route('admin.mail.setting')" :class="{'text-rose-500': $page.component === 'Admin/Mail/Settings'}" class="relative text-white hover:text-rose-500 transition duration-200 text-xl lg:text-sm flex items-center gap-1">
                            <fa icon="fa-envelope" class="font-light text-sm z-10" /> <fa icon="fa-gear" class="font-light text-[10px] absolute top-[-3px] left-[7px] z-50 shadow" /> Mail Settings </Link>
                    </li>
                    <!--------------------------------------------------------------------------
                    | Mail Setting Route End
                    ---------------------------------------------------------------------------->
                    <!--------------------------------------------------------------------------
                  | Maintenance Route Start
                  ---------------------------------------------------------------------------->
                    <li v-if="$page.props.user_role_check.toLowerCase() === 'admin'" class="mb-8">
                        <Link :href="route('admin.maintenance.index')" :class="{'text-rose-500': $page.component === 'Admin/Maintenance/Settings'}" class="relative text-white hover:text-rose-500 transition duration-200 text-xl lg:text-sm flex items-center gap-1">
                            <fa icon="fa-screwdriver-wrench" class="font-light text-sm" /> Maintenance</Link>
                    </li>
                    <!--------------------------------------------------------------------------
                    | Maintenance Route End
                    ---------------------------------------------------------------------------->
                </ul>
            </div>

</template>

<script>
    import {Head, Link } from '@inertiajs/inertia-vue3';
    export default {
        components:{
            Head, Link,
        },
        data(){
            return{
                submenuOpen: false,
                postSubmenuOpen: false,
                userSubmenuOpen: false,
                membersSubmenuOpen: false,
                securitySubmenuOpen: false,
                backupSubmenuOpen:false,
                scheduleSubmenuOpen:false,
                mailSettingSubmenuOpen:false,
                permissionArr: []
            }
        },

        mounted(){
           this.$page.props.userPermissions.forEach(item =>
                this.permissionArr.push(item.name),
           )
        }
    }
</script>

<style>
p{
    margin: 0!important;
    padding: 0!important;
}
    .menubar ul li a{
        /*font-size: 1rem;*/
        letter-spacing: 0.8px;
    }
    .menubar ul .submenu li a{
        /*font-size: 0.8rem!important;*/
    }
    .menubar ul .submenu li{
       margin-bottom: 20px;
    }
    .slide-fade-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.3s ease-in;
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateY(-30px);
        opacity: 0;
    }

</style>
