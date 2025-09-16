<template>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex items-center mb-6">
                <router-link
                    to="/userslist"
                    class="text-blue-500 hover:text-blue-700 mr-4"
                >
                    ← Back to Users
                </router-link>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
            </div>

            <div v-if="loading" class="text-center py-8">
                <span class="text-gray-500">Loading user details...</span>
            </div>

            <div
                v-if="error"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
            >
                {{ error }}
            </div>

            <div
                v-if="user && !loading"
                class="bg-white shadow-md rounded-lg overflow-hidden"
            >
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ user.name }}
                    </h2>
                </div>

                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                ID
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ user.id }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Name
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ user.name }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ user.email }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Email Verified
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span
                                    v-if="user.email_verified_at"
                                    class="text-green-600"
                                >
                                    {{ formatDate(user.email_verified_at) }}
                                </span>
                                <span v-else class="text-red-600"
                                    >Not verified</span
                                >
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Created At
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ formatDate(user.created_at) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Updated At
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ formatDate(user.updated_at) }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t flex justify-between">
                    <div class="space-x-3">
                        <router-link
                            :to="`/users/${user.id}/edit`"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Edit User
                        </router-link>

                        <button
                            @click="confirmDelete"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Delete User
                        </button>
                    </div>

                    <router-link
                        to="/userslist"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Back to List
                    </router-link>
                </div>
            </div>

            <div
                v-if="showDeleteModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center"
            >
                <div class="bg-white p-6 rounded-lg shadow-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Confirm Delete
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Delete {{ user?.name }}?
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="cancelDelete"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deleteUser"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserShow",
    data() {
        return {
            user: null,
            loading: true,
            error: null,
            showDeleteModal: false,
        };
    },
    async mounted() {
        await this.fetchUser();
    },
    methods: {
        async fetchUser() {
            try {
                this.loading = true;
                const userId = this.$route.params.id;
                const token = localStorage.getItem("token");

                const response = await axios.get(`/api/users/${userId}`, {
                    headers: { Authorization: `Bearer ${token}` },
                });

                this.user = response.data.data || response.data;
            } catch (error) {
                this.error =
                consele.log('here?');
                    error.response?.data?.message || "Failed to fetch user";
                if (error.response?.status === 401) {
                    this.$router.push("/login");
                } else if (error.response?.status === 404) {
                    this.error = "User not found";
                }
            } finally {
                this.loading = false;
            }
        },
        confirmDelete() {
            this.showDeleteModal = true;
        },
        cancelDelete() {
            this.showDeleteModal = false;
        },
        async deleteUser() {
            try {
                const token = localStorage.getItem("token");
                await axios.delete(`/api/users/${this.user.id}`, {
                    headers: { Authorization: `Bearer ${token}` },
                });

                this.cancelDelete();
                this.$router.push("/userslist");
            } catch (error) {
                this.error =
                    error.response?.data?.message || "cant delete user";
                this.cancelDelete();
            }
        },
        formatDate(dateString) {
            if (!dateString) return "N/A";
            return new Date(dateString).toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            });
        },
    },
};
</script>
