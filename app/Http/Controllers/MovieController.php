<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\MoviePoster;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        $movies->transform(function ($movie) {
            $movie->poster = asset('storage/poster/' . $movie->poster);
            return $movie;
        });

        return response()->json($movies)->setStatusCode(200);

    }

    public function showbyId($id)
    {
        $movie = Movie::find($id);
        return response()->json($movie)->setStatusCode(200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'genre' => 'required',
            'year' => 'required',
            'poster' => 'required|array',
            'poster.*' => 'required',
        ]);

        $movie = Movie::create([
            'title' => $request->title,
            'genre' => $request->genre,
            'year' => $request->year
        ]);

        $filenames = [];

        if ($request->hasFile('poster')) {
            foreach ($request->file('poster') as $file) {
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->storeAs('public/posters', $filename);
                $filenames[] = $filename;

                MoviePoster::create([
                    'movie_id' => $movie->id,
                    'poster' => $filename,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Unknown Poster',
            ], 400);
        }
        


        return response()->json([
            'message' => 'Movie created successfully',
            'data' => $movie,
            'poster_url'  => array_map(function ($filename) {
                return asset('storage/poster/' . $filename);
            }, $filenames),
    ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'genre' => 'required',
            'year' => 'required',
            'poster' => 'required'
        ]);

        $movie = Movie::find($id);

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(storage_path('app/public/poster'), $filename);
        } else {
            $filename = $movie->poster;
        }

        $movie = Movie::where('id', $id)->update([
            'title' => $request->title,
            'genre' => $request->genre,
            'year' => $request->year,
            'poster' => $filename
        ]);

        return response()->json([
            'message' => 'Movie updated successfully',
            'data' => $movie
        ], 200);
    }


    public function destroy($id)
    {
        $movie = Movie::find($id);
        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully'], 200);
    }
}
