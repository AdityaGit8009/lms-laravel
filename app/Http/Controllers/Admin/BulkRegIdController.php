<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BulkRegIdController extends Controller
{
  public function bulkUpdateRegId(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:4096'],
    ], [
      'csv_file.required' => 'CSV file is required.',
      'csv_file.mimes'    => 'Only CSV file is allowed.',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status'  => false,
        'message' => 'Validation error.',
        'errors'  => $validator->errors(),
      ], 422);
    }

    $file = $request->file('csv_file');
    $rows = array_map('str_getcsv', file($file->getRealPath()));

    if (empty($rows) || count($rows) < 2) {
      return response()->json([
        'status'  => false,
        'message' => 'CSV file is empty or invalid.',
      ], 422);
    }

    $headers = array_map(fn($header) => strtolower(trim($header)), $rows[0]);

    $requiredColumns = [
      'stu_mobile',
      'stu_regid',
    ];

    foreach ($requiredColumns as $column) {
      if (!in_array($column, $headers, true)) {
        return response()->json([
          'status'  => false,
          'message' => "CSV must contain column: {$column}",
        ], 422);
      }
    }

    $columnIndex = array_flip($headers);

    $updatedCount = 0;
    $skippedRows = [];

    DB::beginTransaction();

    try {
      foreach (array_slice($rows, 1) as $rowNumber => $row) {
        $excelRow = $rowNumber + 2;

        $stuMobile = preg_replace('/\D+/', '', trim($row[$columnIndex['stu_mobile']] ?? ''));
        $stuRegid  = trim($row[$columnIndex['stu_regid']] ?? '');

        if ($stuMobile === '' || $stuRegid === '') {
          $skippedRows[] = "Row {$excelRow}: Missing required data.";
          continue;
        }

        if (strlen($stuMobile) !== 10) {
          $skippedRows[] = "Row {$excelRow}: Mobile must be 10 digits.";
          continue;
        }

        $student = DB::table('tbl_student')
          ->where('stu_mobile', $stuMobile)
          ->where('del_status', 'N')
          ->first();

        if (!$student) {
          $skippedRows[] = "Row {$excelRow}: Student not found for mobile {$stuMobile}.";
          continue;
        }

        $regIdExists = DB::table('tbl_student')
          ->where('stu_regid', $stuRegid)
          ->where('stu_id', '!=', $student->stu_id)
          ->exists();

        if ($regIdExists) {
          $skippedRows[] = "Row {$excelRow}: RegID already exists.";
          continue;
        }

        DB::table('tbl_student')
          ->where('stu_id', $student->stu_id)
          ->update([
            'stu_regid'   => $stuRegid,
            'updated_at'  => now(),
          ]);

        $updatedCount++;
      }

      DB::commit();

      $message = "{$updatedCount} student(s) RegID updated successfully.";
      if (!empty($skippedRows)) {
        $message .= ' Some rows were skipped: ' . implode(' | ', array_slice($skippedRows, 0, 10));
      }

      return response()->json([
        'status'        => true,
        'message'       => $message,
        'updated_count' => $updatedCount,
        'skipped_rows'  => $skippedRows,
      ]);
    } catch (\Throwable $e) {
      DB::rollBack();

      return response()->json([
        'status'  => false,
        'message' => 'Something went wrong while updating RegID.',
        'error'   => config('app.debug') ? $e->getMessage() : null,
      ], 500);
    }
  }

  public function downloadTemplate()
  {
    $headers = [
      'Content-Type'        => 'text/csv',
      'Content-Disposition' => 'attachment; filename=bulk_regid_update_template.csv',
    ];

    $callback = function () {
      $file = fopen('php://output', 'w');

      fputcsv($file, [
        'stu_mobile',
        'stu_regid',
      ]);

      fputcsv($file, [
        '9876543210',
        'REG-1001',
      ]);

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }
}
