</div>

    <!-- Footer -->
    <footer class="text-center py-4 mt-5" style="background: rgba(10, 14, 39, 0.95); border-top: 1px solid var(--border-color); backdrop-filter: blur(20px);">
        <div class="container">
            <p class="mb-2" style="color: var(--text-secondary);">
                &copy; 2024 Quick Puff Vape Shop. All rights reserved.
            </p>
            <small style="color: var(--text-muted);">
                <i class="fas fa-bolt me-1"></i> 
                Powered by Quick Puff POS System v1.0.0
            </small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
    $(document).ready(function() {
        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            $('.alert').each(function() {
                $(this).css('transition', 'opacity 0.5s ease');
                $(this).css('opacity', '0');
                setTimeout(() => $(this).remove(), 500);
            });
        }, 5000);

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 76
                }, 1000);
            }
        });

        // Add loading state to buttons
        $('button[type="submit"]').on('click', function() {
            var $btn = $(this);
            var originalText = $btn.html();
            
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
            
            // Re-enable after 10 seconds as fallback
            setTimeout(function() {
                $btn.prop('disabled', false);
                $btn.html(originalText);
            }, 10000);
        });

        // Confirm delete actions
        $('.btn-delete').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });

        // Print functionality
        $('.btn-print').on('click', function() {
            window.print();
            return false;
        });

        // Table row hover effects
        $('.table tbody tr').hover(
            function() {
                $(this).css('transition', 'background-color 0.3s ease');
            },
            function() {
                $(this).css('transition', 'background-color 0.3s ease');
            }
        );

        // Form validation enhancement
        $('form').on('submit', function() {
            var $form = $(this);
            var isValid = true;

            $form.find('input[required], select[required], textarea[required]').each(function() {
                var $input = $(this);
                if (!$input.val().trim()) {
                    $input.addClass('is-invalid');
                    isValid = false;
                } else {
                    $input.removeClass('is-invalid').addClass('is-valid');
                }
            });

            return isValid;
        });

        // Clear validation on input
        $('input, select, textarea').on('input', function() {
            $(this).removeClass('is-invalid');
        });

        // Number input validation
        $('input[type="number"]').on('input', function() {
            var value = parseFloat($(this).val());
            var min = parseFloat($(this).attr('min'));
            var max = parseFloat($(this).attr('max'));

            if (!isNaN(value)) {
                if (!isNaN(min) && value < min) {
                    $(this).val(min);
                } else if (!isNaN(max) && value > max) {
                    $(this).val(max);
                }
            }
        });

        // Search functionality
        $('.search-input').on('keyup', function() {
            var searchTerm = $(this).val().toLowerCase();
            var $items = $('.searchable-item');

            $items.each(function() {
                var $item = $(this);
                var text = $item.text().toLowerCase();

                if (text.includes(searchTerm)) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        });

        // Filter functionality
        $('.filter-select').on('change', function() {
            var filterValue = $(this).val();
            var $items = $('.filterable-item');

            $items.each(function() {
                var $item = $(this);
                var itemValue = $item.data('filter') || '';

                if (filterValue === '' || itemValue === filterValue) {
                    $item.show();
                } else {
                    $item.hide();
                }
            });
        });

        // Export functionality
        $('.btn-export').on('click', function() {
            var format = $(this).data('format') || 'csv';
            var tableId = $(this).data('table') || 'dataTable';
            
            if (format === 'csv') {
                exportTableToCSV(tableId);
            } else if (format === 'excel') {
                exportTableToExcel(tableId);
            }
        });

        // Export table to CSV
        function exportTableToCSV(tableId) {
            var $table = $('#' + tableId);
            var csv = [];
            
            $table.find('thead tr').each(function() {
                var row = [];
                $(this).find('th').each(function() {
                    row.push($(this).text().trim());
                });
                csv.push(row.join(','));
            });
            
            $table.find('tbody tr').each(function() {
                var row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text().trim());
                });
                csv.push(row.join(','));
            });
            
            downloadCSV(csv.join('\n'), 'export.csv');
        }

        // Download CSV file
        function downloadCSV(csv, filename) {
            var csvFile = new Blob([csv], { type: 'text/csv' });
            var downloadLink = document.createElement('a');
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                $('form').first().submit();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                $('.modal').modal('hide');
            }
        });

        // Initialize date pickers
        $('.date-picker').each(function() {
            $(this).attr('type', 'date');
        });

        // Initialize time pickers
        $('.time-picker').each(function() {
            $(this).attr('type', 'time');
        });

        // Password strength indicator
        $('.password-input').on('input', function() {
            var password = $(this).val();
            var strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;
            
            var $indicator = $(this).siblings('.password-strength');
            if ($indicator.length === 0) {
                $indicator = $('<div class="password-strength mt-1"></div>');
                $(this).after($indicator);
            }
            
            var strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'][strength];
            var strengthColor = ['#FF4757', '#FFB400', '#FFA502', '#00D68F', '#4A90E2'][strength];
            
            $indicator.html('<small>Password Strength: ' + strengthText + '</small>');
            $indicator.find('small').css('color', strengthColor);
        });
    });

    // Print styles
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });

    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
    </script>

    <style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        .printing {
            background: white !important;
            color: black !important;
        }
        
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
        
        .btn, .navbar, footer {
            display: none !important;
        }
    }
    </style>
</body>
</html>
