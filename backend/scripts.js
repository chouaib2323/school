function showForm(operationId) {
    // Hide all operation divs
    var operations = document.querySelectorAll('.operation');
    operations.forEach(function(operation) {
        operation.classList.add('hidden');
    });

    // Show the selected operation
    var selectedOperation = document.getElementById(operationId);
    if (selectedOperation) {
        selectedOperation.classList.remove('hidden');
    }
}
