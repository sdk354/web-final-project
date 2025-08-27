<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 transform transition-all scale-95 sm:scale-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">Book Facility</h2>
        <form action="../core/book_facility.php" method="POST" class="space-y-4">
            <!-- Hidden input stays OUTSIDE the container we overwrite -->
            <input type="hidden" name="facility_id" id="facilityId">
            <!-- This container is safe to overwrite -->
            <div id="facilityFieldContainer"></div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date"
                       class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" name="start"
                           class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required value="10:00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" name="end"
                           class="mt-1 block w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required value="12:00">
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" id="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>
