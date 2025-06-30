@extends('layouts.stylist')

@section('title', 'Stylist Lookbook - Padu Padan')

@section('content')
    <style>
        .content-block {
            background-color: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            min-height: calc(100vh - 40px);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .lookbook-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-right: 20px;
        }

        .lookbook-header h1 {
            font-size: 1.8em;
            color: #333;
            margin: 0;
            font-weight: 600;
        }

        .lookbook-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding-top: 10px;
            padding-bottom: 20px;
            flex-grow: 1;
        }

        .outfit-card {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            border: 1px solid #eee;
            position: relative;
        }

        .outfit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .outfit-card::before,
        .outfit-card::after {
            content: '';
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #f4bc43;
        }

        .outfit-card::before {
            top: 0;
        }

        .outfit-card::after {
            bottom: 0;
        }

        .outfit-card img {
            width: 100%;
            height: 280px;
            object-fit: contain;
            display: block;
            padding: 10px;
            box-sizing: border-box;
        }

        .outfit-card .outfit-info {
            padding: 15px;
            text-align: center;
        }

        .outfit-card .outfit-info .designer-name {
            font-weight: 600;
            color: #333;
            margin-top: 5px;
            font-size: 1em;
        }

        .create-button-fixed {
            position: fixed;
            right: 40px;
            bottom: 40px;
            z-index: 1000;
        }

        .create-button {
            background-color: #F4BC43;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 9999px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1em;
        }

        .create-button:hover {
            background-color: #e0a830;
            transform: translateY(-2px);
        }

        .empty-lookbook-message {
            text-align: center;
            font-style: italic;
            color: #666;
            margin-top: 50px;
            width: 100%;
            grid-column: 1 / -1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .lookbook-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 15px;
            }

            .outfit-card img {
                height: 180px;
            }

            .create-button-fixed {
                right: 20px;
                bottom: 20px;
            }

            .create-button {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .lookbook-grid {
                grid-template-columns: 1fr;
            }

            .outfit-card img {
                height: 150px;
            }

            .create-button-fixed {
                right: 15px;
                bottom: 15px;
            }
        }
    </style>

    <div class="content-block">
        <div class="lookbook-header">
            <h1>Lookbook</h1>
        </div>

        <div class="lookbook-grid">
            @forelse($lookbooks as $lookbook)
                <a href="{{ route('stylist.lookbook.show', $lookbook->idLookbook) }}" class="outfit-card">
                    @if ($lookbook->imgLookbook)
                        <img src="{{ asset('storage/' . $lookbook->imgLookbook) }}" alt="{{ $lookbook->nama }}">
                    @else
                        <img src="https://via.placeholder.com/300x280?text=No+Image" alt="No Image Available">
                    @endif
                    <div class="outfit-info">
                        <div class="designer-name">{{ $lookbook->nama }}</div>
                    </div>
                </a>
            @empty
                <div class="empty-lookbook-message">
                    You haven't created any lookbooks yet. Go ahead and create one!
                </div>
            @endforelse
        </div>
    </div>

    <div class="create-button-fixed">
        <a href="{{ route('lookbook.create') }}" class="create-button">
            +
        </a>
    </div>
@endsection
