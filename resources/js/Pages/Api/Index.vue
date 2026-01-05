<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout>
        <Head title="API" />
        <div
            class="api-docs__layout layout__container mx-auto flex min-h-[calc(100vh-4rem)] gap-6 px-6 py-8"
        >
            <aside class="hidden w-64 shrink-0 md:block">
                <div
                    class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-card)] p-4"
                >
                    <p
                        class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                    >
                        API Docs
                    </p>
                    <nav class="mt-4 space-y-2 text-sm">
                        <a
                            href="#users"
                            class="flex items-center justify-between rounded-xl border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-2 text-[var(--color-text)]"
                        >
                            Users
                            <span
                                class="rounded-full border border-[var(--color-border)] px-2 py-0.5 text-[10px] uppercase tracking-[0.2em] text-[var(--color-muted)]"
                            >
                                8
                            </span>
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="flex-1 space-y-12">
                <header class="space-y-4">
                    <p
                        class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                    >
                        API Reference
                    </p>
                    <h1 class="text-4xl font-semibold text-[var(--color-text)]">
                        Users
                    </h1>
                    <p class="max-w-3xl text-sm leading-relaxed text-[var(--color-muted)]">
                        The Users API lets you read, update, and deactivate user
                        records. Each request is authenticated via an access
                        key and scoped to the active organization.
                    </p>
                </header>

                <section
                    id="users"
                    class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                >
                    <div class="flex flex-col gap-4">
                        <div
                            class="flex flex-wrap items-center gap-3 text-sm text-[var(--color-muted)]"
                        >
                            <span
                                class="badge badge--primary"
                            >
                                Users
                            </span>
                            <span>Endpoints for reading and managing user data.</span>
                        </div>
                        <div
                            class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] px-5 py-4 text-sm text-[var(--color-muted)]"
                        >
                            <p class="font-semibold text-[var(--color-text)]">
                                Tip
                            </p>
                            <p class="mt-1">
                                The various API methods for accessing user
                                information.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="space-y-10">
                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--primary"
                            >
                                GET
                            </span>
                            <p class="text-lg font-semibold">Me</p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/me
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Find out who the currently logged in user is, based
                            off the access key you're authenticating with.
                        </p>

                        <div class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="space-y-4">
                                <h3
                                    class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                                >
                                    Response Codes
                                </h3>
                                <div
                                    class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-4 text-sm"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <span class="font-semibold">200 OK</span>
                                        <span class="text-[var(--color-muted)]"
                                            >Successfully retrieved the logged
                                            in user</span
                                        >
                                    </div>
                                </div>
                                <h3
                                    class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                                >
                                    Response Body
                                </h3>
                                <div
                                    class="overflow-hidden rounded-2xl border border-[var(--color-border)]"
                                >
                                    <table
                                        class="w-full text-left text-xs text-[var(--color-text)]"
                                    >
                                        <thead
                                            class="bg-[var(--color-surface)] text-[var(--color-muted)]"
                                        >
                                            <tr>
                                                <th class="px-4 py-2">field</th>
                                                <th class="px-4 py-2">type</th>
                                                <th class="px-4 py-2">
                                                    description
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--color-border)]">
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">id</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The unique identifier of the
                                                    user
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">email</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The user's email address
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    first_name
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The user's first name
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    last_name
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The user's last name
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    avatar_url
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The URL to the user's square
                                                    avatar
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    sign_up_date
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The date (YYYY-MM-DD
                                                    HH:MM:SS) when the user
                                                    registered
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    last_active_date
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    The date (YYYY-MM-DD
                                                    HH:MM:SS) when the user last
                                                    used Hail
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    wants_organisation_emails
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    boolean
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    Whether the user wishes to
                                                    receive organisation-related
                                                    email notifications
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    wants_promotion_emails
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    boolean
                                                </td>
                                                <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                    Whether the user wishes to
                                                    receive promotion emails
                                                    from Hail
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3
                                    class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                                >
                                    Example Response
                                </h3>
                                <div
                                    class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                                >
                                    <pre
                                        class="text-xs leading-relaxed text-[var(--color-text)]"
                                    >{
    "id": "rp2x5yn",
    "email": "test@test.com",
    "first_name": "Buck",
    "last_name": "Robel",
    "avatar_url": "http://lorempixel.com/150/150/",
    "sign_up_date": "2014-01-01 01:02:03",
    "last_active_date": "2014-01-02 04:43:33",
    "wants_organisation_emails": true,
    "wants_promotion_emails": true
}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--primary"
                            >
                                GET
                            </span>
                            <p class="text-lg font-semibold">Get a user</p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Returns the requested user. If the user is the
                            current account, the response mirrors
                            <code class="text-[var(--color-text)]">v1/me</code>.
                            Otherwise, a public subset is returned.
                        </p>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Body (public subset)
                            </h3>
                            <div
                                class="overflow-hidden rounded-2xl border border-[var(--color-border)]"
                            >
                                <table
                                    class="w-full text-left text-xs text-[var(--color-text)]"
                                >
                                    <thead
                                        class="bg-[var(--color-surface)] text-[var(--color-muted)]"
                                    >
                                        <tr>
                                            <th class="px-4 py-2">field</th>
                                            <th class="px-4 py-2">type</th>
                                            <th class="px-4 py-2">
                                                description
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--color-border)]">
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">id</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The unique identifier of the
                                                user
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                first_name
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The user's first name
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                last_name
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The user's last name
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                avatar_url
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The URL to the user's avatar
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                last_active_date
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The date (YYYY-MM-DD HH:MM:SS)
                                                the user was last active
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Example Response
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                            >
                                <pre
                                    class="text-xs leading-relaxed text-[var(--color-text)]"
                                >{
    "id": "rp2x5yn",
    "first_name": "Buck",
    "last_name": "Robel",
    "avatar_url": "http://lorempixel.com/150/150/",
    "last_active_date": "1992-01-10 04:05:06"
}</pre>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--secondary"
                            >
                                PUT
                            </span>
                            <p class="text-lg font-semibold">Update a user</p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Allows a user to update their settings. You must be
                            authenticated as the user under edit for the
                            request to be authorised.
                        </p>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Request Body
                            </h3>
                            <div
                                class="overflow-hidden rounded-2xl border border-[var(--color-border)]"
                            >
                                <table
                                    class="w-full text-left text-xs text-[var(--color-text)]"
                                >
                                    <thead
                                        class="bg-[var(--color-surface)] text-[var(--color-muted)]"
                                    >
                                        <tr>
                                            <th class="px-4 py-2">field</th>
                                            <th class="px-4 py-2">required</th>
                                            <th class="px-4 py-2">type</th>
                                            <th class="px-4 py-2">
                                                description
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--color-border)]">
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                first_name
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The user's first name
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">last_name</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The user's last name
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">password</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The user's password
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                wants_organisation_emails
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">boolean</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                Whether the user wants
                                                organisation-related email
                                                notifications
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                wants_promotion_emails
                                            </td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">boolean</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                Whether the user wants
                                                promotional emails from Hail
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Example Request
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                            >
                                <pre
                                    class="text-xs leading-relaxed text-[var(--color-text)]"
                                >{
    "first_name": "Sheldon"
}</pre>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">200 OK</span>
                                    <span class="text-[var(--color-muted)]">
                                        User successfully updated
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>400 Bad Request</span>
                                    <span>Validation of the provided data failed</span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The authenticated user is not the user
                                        being edited
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--secondary"
                            >
                                POST
                            </span>
                            <p class="text-lg font-semibold">
                                Change a user's avatar
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/avatar
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Upload a new avatar image (jpeg, gif, or png).
                        </p>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Request Body
                            </h3>
                            <div
                                class="overflow-hidden rounded-2xl border border-[var(--color-border)]"
                            >
                                <table
                                    class="w-full text-left text-xs text-[var(--color-text)]"
                                >
                                    <thead
                                        class="bg-[var(--color-surface)] text-[var(--color-muted)]"
                                    >
                                        <tr>
                                            <th class="px-4 py-2">field</th>
                                            <th class="px-4 py-2">required</th>
                                            <th class="px-4 py-2">type</th>
                                            <th class="px-4 py-2">
                                                description
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--color-border)]">
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">image</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">yes</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">file</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The image for the user's avatar
                                                (jpeg, gif, or png)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">200 OK</span>
                                    <span class="text-[var(--color-muted)]">
                                        The image was updated successfully
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>400 Bad Request</span>
                                    <span>Validation of the provided data failed</span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The authenticated user is not the user
                                        being edited
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--danger"
                            >
                                DELETE
                            </span>
                            <p class="text-lg font-semibold">
                                Delete a user's avatar
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/avatar
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Users can delete their avatars.
                        </p>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">204 No Content</span>
                                    <span class="text-[var(--color-muted)]">
                                        Avatar deleted successfully
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The authenticated user is not the user
                                        being edited
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--danger"
                            >
                                DELETE
                            </span>
                            <p class="text-lg font-semibold">Deactivate a user</p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}
                            </code>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-[var(--color-muted)]">
                            Deactivates the user, revokes access keys, and
                            prevents new keys from being issued.
                        </p>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Request Body
                            </h3>
                            <div
                                class="overflow-hidden rounded-2xl border border-[var(--color-border)]"
                            >
                                <table
                                    class="w-full text-left text-xs text-[var(--color-text)]"
                                >
                                    <thead
                                        class="bg-[var(--color-surface)] text-[var(--color-muted)]"
                                    >
                                        <tr>
                                            <th class="px-4 py-2">field</th>
                                            <th class="px-4 py-2">required</th>
                                            <th class="px-4 py-2">type</th>
                                            <th class="px-4 py-2">
                                                description
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--color-border)]">
                                        <tr>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">reason</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">no</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">string</td>
                                            <td class="border-t border-[var(--color-border)] px-4 py-2">
                                                The reason for deactivating
                                                (string, optional)
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Example Request
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                            >
                                <pre
                                    class="text-xs leading-relaxed text-[var(--color-text)]"
                                >{
    "reason": "Tom made fun of me too much."
}</pre>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">204 No Content</span>
                                    <span class="text-[var(--color-muted)]">
                                        User deactivated successfully
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The authenticated user is not the user
                                        being deactivated
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--primary"
                            >
                                GET
                            </span>
                            <p class="text-lg font-semibold">
                                List a user's organisations
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/organisations
                            </code>
                        </div>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">200 OK</span>
                                    <span class="text-[var(--color-muted)]">
                                        Listing was successful
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The requester isn't the user being
                                        queried
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-[var(--color-muted)]">
                                Returns an array of organisations with a role
                                field. See the Organisations documentation for
                                the field list.
                            </p>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--primary"
                            >
                                GET
                            </span>
                            <p class="text-lg font-semibold">
                                List items in a user's inbox for an organisation
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/messages/organisation/{org_id}
                            </code>
                        </div>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">200 OK</span>
                                    <span class="text-[var(--color-muted)]">
                                        Listing was successful
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The requester isn't the user being
                                        queried
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-[var(--color-muted)]">
                                Returns an array of messages with event details,
                                actors, and timestamps.
                            </p>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--primary"
                            >
                                GET
                            </span>
                            <p class="text-lg font-semibold">
                                Count items in a user's inbox
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/messages/organisation/{org_id}/count
                            </code>
                        </div>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">200 OK</span>
                                    <span class="text-[var(--color-muted)]">
                                        The request was successful
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The requester isn't the user being
                                        queried
                                    </span>
                                </div>
                            </div>
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Example Response
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5"
                            >
                                <pre
                                    class="text-xs leading-relaxed text-[var(--color-text)]"
                                >{
    "count": 7
}</pre>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8"
                    >
                        <div class="flex flex-wrap items-center gap-4">
                            <span
                                class="badge badge--danger"
                            >
                                DELETE
                            </span>
                            <p class="text-lg font-semibold">
                                Read items in a user's inbox
                            </p>
                            <code
                                class="rounded-full border border-[var(--color-border)] bg-[var(--color-surface)] px-3 py-1 text-xs text-[var(--color-text)]"
                            >
                                v1/users/{id}/messages/organisation/{org_id}/unread
                            </code>
                        </div>
                        <div class="mt-6 space-y-4">
                            <h3
                                class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]"
                            >
                                Response Codes
                            </h3>
                            <div
                                class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] p-5 text-sm"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold">204 No Content</span>
                                    <span class="text-[var(--color-muted)]">
                                        The inbox was read successfully
                                    </span>
                                </div>
                                <div
                                    class="mt-2 flex items-center justify-between text-[var(--color-muted)]"
                                >
                                    <span>403 Not Authorised</span>
                                    <span>
                                        The requester isn't the user being
                                        queried
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
