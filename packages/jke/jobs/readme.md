#Instalation

1. Create desired_jobs database table.

2. Add the following ENV variables in .env file
DB_JOBS_CONNECTION = mysql
DB_JOBS_HOST = your-host
DB_JOBS_DATABASE = your-database
DB_JOBS_USERNAME = your-username
DB_JOBS_PASSWORD = your-password

3. Run php artisan vendor:publish to transfer all the migrations to the code project migrations.

4. Run php artisan:migrate to migrate the tables.
5. Install mysql native driver
#Available Routes - Desired Jobs
1.  description: get all desired jobs
    url: base_url/jobs
    method: GET

2.  description: get selected desired jobs by user_id
    url: base_url/jobs/{user_id}
    method: GET

3.  description: create new desired job for user
    url: base_url/jobs
    method: POST
    params: user_id, desired_job_id
    data: "user_id" (verchar), "desired_job_id" (integer). User_id and desired_job_id is required.
    format: {  
              "user_id":"582210eb-7daa-46d0-acf8-23071e59a9a2",
              "desired_job_id":"1"
            }

4.  description: delete selected desired job by userId and desiredJobId
    url: base_url/jobs/{userId}/{desiredJobId}
    method: DELETE

#Available Routes - Experiences
1.  description: get all experiences
    url: base_url/experiences
    method: GET
    
2.  description: get all selected experiences by userId
    url: base_url/experiences/{userId}
    method: GET
    
3.  description: create new experiences for user
    url: base_url/experiences
    method: POST
    params: user_id, experience_id, position_held, years_of_experience, management.
    data: "user_id" (verchar), "experience_id" (integer), "position_held" (verchar), "years_of_experience" (integer), management (value: 1/0 (yes/no)).
    User_id, experience_id and management are required.
    format: {
              "user_id":"582210eb-7daa-46d0-acf8-23071e59a9a2",
              "experience_id":"3",
              "position_held":"position_held",
              "years_of_experience":"10",
              "management":"0"
            }
4.  description: get specified experience for user by userId and experienceId (id from pivot table)
    url: base_url/experiences/{userId}/{experienceId}
    method: GET
              
5.  description: edit existing experiences for user_id
    url: base_url/experiences/{user_id}
    method: POST
    params: id, user_id, experience_id, position_held, years_of_experience, management.
    data: "id" (integer), "user_id" (verchar), "experience_id" (integer), "position_held" (verchar), "years_of_experience" (integer), management (value: 1/0 (yes/no)).
    Id, user_id and experience_id are required.
    format: {  
              "id":"1",
              "user_id":"582210eb-7daa-46d0-acf8-23071e59a9a2",
              "experience_id":"3",
              "position_held":"position_held",
              "years_of_experience":"10",
              "management":"0"
            }
            
6.  description: delete selected experiences by userId and experienceId (id from pivot table)
    url: base_url/experiences/{userId}/{experienceId}
    method: DELETE
    
#Available Routes - Qualifications
1.  description: get all qualifications
    url: base_url/qualifications
    method: GET
    
2.  description: get  selected qualifications by userId
    url: base_url/qualifications/{userId}
    method: GET
    
3.  description: create new qualifications for user
    url: base_url/qualifications
    method: POST
    params: user_id, qualification_id, rating
    data: "user_id" (verchar), "qualification_id" (integer), "rating" (enum with values: Always, Mostly, N/A, Never, Often, Seldom).
    User_id and qualification_id are required.
    format: {  
              "user_id":"582210eb",
              "qualification_id":"10",
              "rating":"rating"
            }
            
4.  description: edit existing qualifications for userId
    url: base_url/qualifications/{userId}
    method: POST
    params: user_id, qualification_id, rating
    data: "user_id" (verchar), "qualification_id" (integer), "rating" (enum with values: Always, Mostly, N/A, Never, Often, Seldom).
    User_id and qualification_id are required.
    format: {  
              "user_id":"582210eb",
              "qualification_id":"10",
              "rating":"rating"
            }
            
5.  description: delete selected qualifications by userId and qualificationId
    url: base_url/qualifications/{userId}/{qualificationId}
    method: DELETE
    
#Available Routes - Certificates
1.  description: get all certificate types
    url: base_url/certificates
    method: GET
    
2.  description: get all certificates for user by userId.
    url: base_url/certificates/{userId}
    method: GET
    
3.  description: create new certificates for user
    url: base_url/certificates
    method: POST
    params: user_id, certificate_id, certificate_name, certificate_agency, expiration_date, level_of_experience
    data: "user_id" (verchar), "certificate_id" (integer), "certificate_name" (verchar), "certificate_agency" (verchar), "expiration_date" (datetime), 
                    "level_of_experience" (enum with values: None, Some, All. Default is None).
                    User_id, certificate_id are required.
    format: {  
              "user_id":"582210eb",
              "certificate_id":"3",
              "certificate_name":"certificate_name123",
              "certificate_agency":"certificate_agency",
              "expiration_date":"2015-10-05 11:25:04",
              "level_of_experience":"None"
            }
4.  description: get specified certificate for user by userId and certificateId
    url: base_url/references/{userId}/{certificateId} (id from pivot table)
    method: GET
                
5.  description: edit existing certificates for id (where id is unique from pivot - users_certificates table)
    url: base_url/certificates/{userId}
    method: POST
    params: id, user_id, certificate_id, certificate_name, certificate_agency, expiration_date, level_of_experience
    data: "id" (integer), "user_id" (verchar), "certificate_id" (integer), "certificate_name" (verchar), "certificate_agency" (verchar), "expiration_date" (datetime), 
                    "level_of_experience" (enum with values: None, Some, All. Default is None), certificate_verified (true/false). Id, user_id and certificate_id are required.
    format: { 
              "id":"1",
              "user_id":"582210eb",
              "certificate_id":"3",
              "certificate_name":"certificate_name123",
              "certificate_agency":"certificate_agency",
              "expiration_date":"2015-10-05 11:25:04",
              "level_of_experience":"None",
              "certificate_verified":true
            }
            
6.  description: delete selected certificates by userId and certificateId (where certificateId is unique from pivot - users_certificates table)
    url: base_url/certificates/{userId}/{certificateId} (id from pivot table)
    method: DELETE
    
#Available Routes - References
1.  description: get all references for all users
    url: base_url/references
    method: GET
    
2.  description: get all references for user by userId.
    url: base_url/references/{userId}
    method: GET
    
3.  description: create new references for user
    url: base_url/references
    method: POST
    params: user_id, reference_name, reference_phone, reference_email, reference_company, reference_title
    data: "user_id" (verchar), "reference_name" (verchar), "reference_phone" (verchar), "reference_email" (verchar), "reference_company" (verchar), 
                    "reference_title" (verchar). User_id, reference_name and reference_email are required.
    format: {  
            "user_id":"d1e070ad-a28f-4ff4-a7e3-be3cc07443a1",
            "reference_name":"reference_name",
            "reference_phone":"reference_phone",
            "reference_email":"petar@petar.com",
            "reference_company":"reference_company",
            "reference_title":"reference_title"
            }
            
4.  description: get specified reference for user by userId and referenceId
    url: base_url/references/{userId}/{referenceId}
    method: GET
    
5.  description: edit existing reference
     url: base_url/references
     method: POST
     params: id, user_id, reference_name, reference_phone, reference_email, reference_company, reference_title
     data:   id(integer), "user_id" (verchar), "reference_name" (verchar), "reference_phone" (verchar), "reference_email" (verchar), "reference_company" (verchar), 
                     "reference_title" (verchar). Id, user_id, reference_name and reference_email are required.
     format: {  
             "id":"66",
             "user_id":"582210eb",
             "reference_name":"reference_name",
             "reference_phone":"reference_phone",
             "reference_email":"test@test.com",
             "reference_company":"reference_company",
             "reference_title":"reference_title"
             }  
6.  description: delete selected reference by userId and referenceId
    url: base_url/references/{userId}/{referenceId}
    method: DELETE
    
7. description: edit existing reference
   url: base_url/references/verified
   method: POST
   params: id, user_id, reference_verified.
   data:   id(integer), "user_id" (verchar), "reference_verified" (boolean true/false).Id, user_id, reference_verified.
   
   format: {
            "id":"2",
            "user_id":"987322d8-6242-4ae1-b92c-514530cd8fd6",
            "reference_verified":true
           }
           
8. description: get user name by reference ID
   url: base_url/references/user/{referenceId}
   method: GET