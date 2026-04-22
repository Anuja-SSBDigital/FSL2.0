<?php

/**
 * Fluree Schema Predicates for FSL 2.0
 * 
 * This file contains all predicate definitions needed for the Evidence Acceptance system.
 * Execute these against your Fluree instance to create the schema.
 * 
 * Usage:
 * 1. Copy these predicates to your Fluree transaction
 * 2. Or use in: php artisan tinker
 *    $service = app('FlureeService');
 *    foreach ($predicates as $predicate) {
 *        $service->transact([$predicate]);
 *    }
 */

return [
    // ============================================
    // EVIDENCE ACCEPTANCE DETAILS PREDICATES
    // ============================================
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/evidenceid",
        "type" => "uuid",
        "unique" => true,
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/caseno",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/receiptfilepath",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/agencyreferanceno",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/agencyname",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/notes",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/status",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/hash",
        "type" => "string",
        "unique" => true,
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/department_code",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/inst_code",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/div_code",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/noof_exhibits",
        "type" => "long",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/caseassign_userid",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/enteredby",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/createddate",
        "type" => "instant",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "evidence_acceptancedetails/updateddate",
        "type" => "instant",
        "index" => true
    ],
    
    // ============================================
    // CASE TRACKING PREDICATES
    // ============================================
    
    [
        "_id" => "_predicate",
        "name" => "track/caseno",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/status",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/username",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/userid",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/notes",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/statuschangeby",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "track/changed_at",
        "type" => "instant",
        "index" => true
    ],
    
    // ============================================
    // CASE ASSIGNMENT PREDICATES
    // ============================================
    
    [
        "_id" => "_predicate",
        "name" => "assign/caseno",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/statuschangeby",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/userid",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/notes",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/div_code",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/dept_code",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/priority",
        "type" => "string",
        "index" => true
    ],
    
    [
        "_id" => "_predicate",
        "name" => "assign/assigned_at",
        "type" => "instant",
        "index" => true
    ],
];
