<x-supvis.layouts>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg,rgb(33, 226, 62),#2575FC);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        h1 {
            color: linear-gradient(135deg,rgb(33, 226, 62),#2575FC);
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 30px;
            text-align: center;
            padding: 0 20px;
        }

        .sales-list {
            width: 90%;
            max-width: 420px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-left: 20px;
        }

        .sales-item {
            margin: 15px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sales-item:hover {
            background-color: #e8f4ff;
            transform: translateY(-2px);
        }

        input[type="checkbox"] {
            accent-color: #004aad;
            transform: scale(1.3);
            cursor: pointer;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            margin-left: 10px;
            color: #333;
        }

        .completed {
            text-decoration: line-through;
            color: #888;
        }

        button {
            margin-top: 25px;
            padding: 12px 20px;
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease, transform 0.2s ease;
            width: 150px;
            max-width: 100%;
            margin-left: 135px;
        }

        button:hover {
            background: linear-gradient(135deg,rgb(33, 226, 62), #2575FC);
            transform: translateY(-2px);
        }

        #result {
            width: 90%;
            max-width: 420px;
            margin-top: 25px;
            padding: 20px;
            background-color:rgb(0, 0, 0);
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        #checked-names {
            color: #004aad;
            font-weight: 600;
            font-size: 16px;
        }
        
        @media (max-width: 600px) {
            h1 {
                font-size: 22px;
            }
            button {
                font-size: 14px;
                padding: 10px 15px;
            }
            p {
                font-size: 14px;
            }
        }
    </style>

</head>
    <body>
        <h1>Sales Checklist</h1>
        <p>Pilih nama sales yang sudah setoran</p>
        <div class="sales-list">
            @foreach ($sales as $salesperson)
                <div class="sales-item">
                    <input type="checkbox" id="sales{{ $loop->index }}" value="{{ $salesperson->id }}"
                        class="setoran-checkbox" data-sales-id="{{ $salesperson->id }}"
                        {{ $salesperson->is_setoran ? 'checked' : '' }}>
                    <label for="sales{{ $loop->index }}">{{ $salesperson->name }}</label>
                </div>
            @endforeach

        </div>
        <button onclick="submitChecklist()">Submit</button>
        <div id="result" hidden>
            <h2>Checklist Result</h2>
            <p id="checked-names">Tidak ada sales yang dichecklist.</p>
        </div>

        <script>
            function submitChecklist() {
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                const checkedNames = [];
                checkboxes.forEach(checkbox => {
                    const label = checkbox.nextElementSibling;
                    if (checkbox.checked) {
                        checkedNames.push(label.textContent.trim());
                        label.classList.add('completed');
                    } else {
                        label.classList.remove('completed');
                    }
                });
                const resultDiv = document.getElementById('result');
                const namesParagraph = document.getElementById('checked-names');
                if (checkedNames.length > 0) {
                    namesParagraph.textContent = `Sales yang sudah dichecklist: ${checkedNames.join(', ')}`;
                } else {
                    namesParagraph.textContent = 'Tidak ada sales yang dichecklist.';
                }

                resultDiv.hidden = false;
            }

            document.querySelectorAll('.setoran-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const salesId = this.dataset.salesId;
                    const isSetoran = this.checked;
                    fetch(`/update-is-setoran/${salesId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                is_setoran: isSetoran
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Setoran updated successfully.');
                            } else {
                                console.error('Failed to update setoran.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        </script>
</x-supvis.layouts>

