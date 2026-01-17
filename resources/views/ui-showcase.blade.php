<x-layout>
    <div class="min-h-screen bg-background">
        <!-- Header -->
        <div class="bg-surface border-b border-border sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-4 md:px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-900">UI Component Showcase</h1>
                <p class="text-muted-foreground mt-2">Professional Color Palette & Component Reference</p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-6xl mx-auto px-4 md:px-8 py-12">
            <!-- Color Palette Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Color Palette</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Primary -->
                    <div class="space-y-2">
                        <div class="h-24 bg-primary rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Primary</p>
                        <p class="text-xs text-muted-foreground">Muted Navy Blue</p>
                    </div>

                    <!-- Secondary -->
                    <div class="space-y-2">
                        <div class="h-24 bg-secondary rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Secondary</p>
                        <p class="text-xs text-muted-foreground">Slate Blue</p>
                    </div>

                    <!-- Success -->
                    <div class="space-y-2">
                        <div class="h-24 bg-success rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Success</p>
                        <p class="text-xs text-muted-foreground">Green</p>
                    </div>

                    <!-- Warning -->
                    <div class="space-y-2">
                        <div class="h-24 bg-warning rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Warning</p>
                        <p class="text-xs text-muted-foreground">Amber</p>
                    </div>

                    <!-- Danger -->
                    <div class="space-y-2">
                        <div class="h-24 bg-danger rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Danger</p>
                        <p class="text-xs text-muted-foreground">Red</p>
                    </div>

                    <!-- Info -->
                    <div class="space-y-2">
                        <div class="h-24 bg-info rounded-lg shadow-sm border border-border"></div>
                        <p class="text-sm font-semibold text-gray-900">Info</p>
                        <p class="text-xs text-muted-foreground">Blue</p>
                    </div>
                </div>
            </section>

            <!-- Buttons Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Buttons</h2>
                
                <div class="space-y-8">
                    <!-- Primary Buttons -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Primary Buttons</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="px-6 py-2 bg-primary text-primary-foreground font-semibold rounded-lg hover:bg-primary/90 transition">
                                Primary
                            </button>
                            <button class="px-6 py-2 bg-primary/10 text-primary font-semibold rounded-lg hover:bg-primary/20 transition">
                                Secondary
                            </button>
                            <button disabled class="px-6 py-2 bg-muted text-muted-foreground font-semibold rounded-lg cursor-not-allowed opacity-50">
                                Disabled
                            </button>
                        </div>
                    </div>

                    <!-- Status Buttons -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Status Buttons</h3>
                        <div class="flex flex-wrap gap-4">
                            <button class="px-6 py-2 bg-success text-success-foreground font-semibold rounded-lg hover:bg-success/90 transition">
                                Success
                            </button>
                            <button class="px-6 py-2 bg-warning text-warning-foreground font-semibold rounded-lg hover:bg-warning/90 transition">
                                Warning
                            </button>
                            <button class="px-6 py-2 bg-danger text-danger-foreground font-semibold rounded-lg hover:bg-danger/90 transition">
                                Danger
                            </button>
                            <button class="px-6 py-2 bg-info text-info-foreground font-semibold rounded-lg hover:bg-info/90 transition">
                                Info
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Badges Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Badges & Status</h2>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Filled Badges -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Filled Badges</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-success/20 text-success-foreground text-sm font-medium rounded-full">Success</span>
                            <span class="px-3 py-1 bg-warning/20 text-warning-foreground text-sm font-medium rounded-full">Warning</span>
                            <span class="px-3 py-1 bg-danger/20 text-danger-foreground text-sm font-medium rounded-full">Danger</span>
                            <span class="px-3 py-1 bg-info/20 text-info-foreground text-sm font-medium rounded-full">Info</span>
                        </div>
                    </div>

                    <!-- Outlined Badges -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Outlined Badges</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 border border-success text-success-foreground text-sm font-medium rounded-full">Success</span>
                            <span class="px-3 py-1 border border-warning text-warning-foreground text-sm font-medium rounded-full">Warning</span>
                            <span class="px-3 py-1 border border-danger text-danger-foreground text-sm font-medium rounded-full">Danger</span>
                            <span class="px-3 py-1 border border-info text-info-foreground text-sm font-medium rounded-full">Info</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cards Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Cards</h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-surface border border-border rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Default Card</h3>
                        <p class="text-muted-foreground text-sm mt-2">This is a standard card with borders and subtle shadow.</p>
                    </div>

                    <div class="bg-surface-2 border border-border-light rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Surface Card</h3>
                        <p class="text-muted-foreground text-sm mt-2">This card uses the secondary surface color.</p>
                    </div>

                    <div class="bg-success/10 border border-success/30 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-success-foreground">Success Card</h3>
                        <p class="text-success-foreground/80 text-sm mt-2">Used for positive feedback or status.</p>
                    </div>

                    <div class="bg-warning/10 border border-warning/30 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-warning-foreground">Warning Card</h3>
                        <p class="text-warning-foreground/80 text-sm mt-2">Used for caution or alerts.</p>
                    </div>
                </div>
            </section>

            <!-- Forms Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Form Elements</h2>
                
                <div class="max-w-md space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Text Input</label>
                        <input type="text" placeholder="Enter text..." class="w-full px-4 py-2 border border-border rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Select Dropdown</label>
                        <select class="w-full px-4 py-2 border border-border rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none transition">
                            <option>Option 1</option>
                            <option>Option 2</option>
                            <option>Option 3</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Textarea</label>
                        <textarea placeholder="Enter text..." class="w-full px-4 py-2 border border-border rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/30 focus:outline-none transition"></textarea>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="checkbox" class="border-border focus:ring-primary/30">
                        <label for="checkbox" class="text-sm font-medium text-gray-900">Checkbox option</label>
                    </div>
                </div>
            </section>

            <!-- Tables Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Tables</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-surface-2 border-b border-border">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr class="hover:bg-surface-2 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">Item 1</td>
                                <td class="px-6 py-4 text-sm"><span class="px-3 py-1 bg-success/20 text-success-foreground text-xs font-medium rounded-full">Active</span></td>
                                <td class="px-6 py-4 text-sm text-muted-foreground">Jan 16, 2026</td>
                            </tr>
                            <tr class="hover:bg-surface-2 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">Item 2</td>
                                <td class="px-6 py-4 text-sm"><span class="px-3 py-1 bg-warning/20 text-warning-foreground text-xs font-medium rounded-full">Pending</span></td>
                                <td class="px-6 py-4 text-sm text-muted-foreground">Jan 15, 2026</td>
                            </tr>
                            <tr class="hover:bg-surface-2 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">Item 3</td>
                                <td class="px-6 py-4 text-sm"><span class="px-3 py-1 bg-danger/20 text-danger-foreground text-xs font-medium rounded-full">Inactive</span></td>
                                <td class="px-6 py-4 text-sm text-muted-foreground">Jan 14, 2026</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Alerts Section -->
            <section class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Alerts</h2>
                
                <div class="space-y-4">
                    <div class="bg-success/10 border border-success/30 rounded-lg p-4 text-success-foreground">
                        <p class="font-semibold">Success!</p>
                        <p class="text-sm text-success-foreground/80">This is a success message.</p>
                    </div>

                    <div class="bg-warning/10 border border-warning/30 rounded-lg p-4 text-warning-foreground">
                        <p class="font-semibold">Warning</p>
                        <p class="text-sm text-warning-foreground/80">This is a warning message.</p>
                    </div>

                    <div class="bg-danger/10 border border-danger/30 rounded-lg p-4 text-danger-foreground">
                        <p class="font-semibold">Error</p>
                        <p class="text-sm text-danger-foreground/80">This is an error message.</p>
                    </div>

                    <div class="bg-info/10 border border-info/30 rounded-lg p-4 text-info-foreground">
                        <p class="font-semibold">Info</p>
                        <p class="text-sm text-info-foreground/80">This is an informational message.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-layout>
