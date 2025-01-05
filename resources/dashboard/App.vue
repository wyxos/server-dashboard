<template>
    <div class="bg-slate-900 text-white min-h-screen flex">
        <aside>
            <nav class="bg-slate-800">
                <ul>
                    <li>
                        <router-link to="/">Dashboard</router-link>
                    </li>
                    <li>
                        <router-link to="/applications">Applications</router-link>
                    </li>
                    <li>
                        <router-link to="/databases">Databases</router-link>
                    </li>
                    <li>
                        <router-link to="/logs">Logs</router-link>
                    </li>
                    <li>
                        <router-link to="/cron">Cron</router-link>
                    </li>
                    <li>
                        <router-link to="/supervisord">Supervisord</router-link>
                    </li>
                    <li>
                        <router-link to="/settings">Settings</router-link>
                    </li>
                </ul>
            </nav>
        </aside>

        <router-view class="flex-1"></router-view>
    </div>
</template>
