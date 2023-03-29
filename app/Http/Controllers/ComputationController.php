<?php

namespace App\Http\Controllers;

use App\Models\Chemistry;
use App\Models\English;
use App\Models\Mathematics;
use App\Models\Physics;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComputationController extends Controller
{
    public function getStudents() {
        $user = User::find(auth()->id());
        if ($user->role !== "teacher") {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'success' => false,
                'message' => "Unauthorized to access this resource",
            ]);
        }

        $students = User::where('role', 'student')->get();

        return response()->json([
            'success' => true,
            'message' => "Students List",
            'data' => $students,
        ]);
    }

    public function addGrade(Request $request) {
        $request->validate([
            'userId' => ['required', 'exists:users,id'],
            'score' => ['required', 'integer'],
            'grade' => ['required', 'string'],
            'subject' => ['required', 'in:english,chemistry,mathematics,physics'],
        ]);

        $user = User::find(auth()->id());
        // $user = auth()->user();
        if ($user->role !== "teacher") {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'success' => false,
                'message' => "Unauthorized to access this resource",
            ]);
        }

        Switch($request->subject) {
            case "english":
                English::create([
                    'user_id' => $request->userId,
                    'score' => $request->score,
                    'grade' => $request->grade,
                ]);
                break;
            case "mathematics":
                Mathematics::create([
                    'user_id' => $request->userId,
                    'score' => $request->score,
                    'grade' => $request->grade,
                ]);
                break;
            case "physics":
                Physics::create([
                    'user_id' => $request->userId,
                    'score' => $request->score,
                    'grade' => $request->grade,
                ]);
                break;
            case "chemistry":
                Chemistry::create([
                    'user_id' => $request->userId,
                    'score' => $request->score,
                    'grade' => $request->grade,
                ]);
                break;
            default:
                break;
        }

        return response()->json([
            'success' => true,
            'message' => "Student Score Updated",
            'data' => null,
        ]);

    }

    public function results() {
        $user = User::find(auth()->id());

        if ($user->role !== "student") {
            return response()->json([
                'code' => Response::HTTP_UNAUTHORIZED,
                'success' => false,
                'message' => "Unauthorized to access this resource",
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "available results",
            'data' => [
                'mathematics' => [
                    'score' => $user->mathematics->score,
                    'grade' => $user->mathematics->grade,
                ],
                'english' => [
                    'score' => $user->english->score,
                    'grade' => $user->english->grade,
                ],
                'physics' => [
                    'score' => $user->physics->score,
                    'grade' => $user->physics->grade,
                ],
                'chemistry' => [
                    'score' => $user->chemistry->score,
                    'grade' => $user->chemistry->grade,
                ]
                
            ]
        ]);
    }
}
