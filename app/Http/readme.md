#Mailchimp
Put these two lines in .env file

MAILCHIMP_API_KEY = <API KEY> (ex: 9b18fabf4efe9cbf98bc3056c0890168-us12)
MAILCHIMP_GENERAL_LIST_ID = <LIST ID> (ex: 736ff0294b) 
 

#Users
## Routes

Verb	Path	Action	Route Name
GET	/users	index	users.index
POST	/users	store	users.store

PUT/PATCH	/users/{users}	update	users.update
DELETE	/users/{users}	destroy	users.destroy

Not implemented:

    GET	/users/{users}	show	users.show
    GET	/users/{users}/edit	edit	users.edit
    GET	/users/create	create	users.create


#Token
## Functionality

    $token->generate();
    

#Dependency packages

    composer require jenssegers/mongodb
    composer require ramsey/uuid
    composer requireleague/flysystem
    composer require league/fractal
    
#Add link to front end in .env file
Example: FRONT_URL = http://localhost:9001

#Add default number of items
Example: DEFAULT_PAGE_ITEMS=15

#Search function
#Available Routes
1.  description: search users with desired jobs
    url: base_url/users
    method: GET    
    params: first_last_name, email, desired_job (desired job id), job_title, seeking_job (value: yes/no), 
    certificate_type (id of Certificate), certificate_name, certificate_agency, certificate_expiration_date (date)
    order_by (values: first_last_name, created_at, Technical, Critical, Assessment), order (values: ASC, DESC)
    extra: query parameter multi with values true and false. multi=true is preventing multiple assigns.
    matching: desired_job (desired job id), technical_knowledge (values:1,2,3,4,5), critical_skills (values:1,2,3,4,5), assessment (values:1,2,3,4,5)
    Example:
            base_url/users?first_last_name=Admin Master            
            &email=admin@domain.ltd
            &desired_job=1
            &job_title=Developer
            &seeking_job=no
            &certificate_type=1
            &certificate_name=certificate_name
            &certificate_agency=certificate_agency
            &certificate_expiration_date=some_date
            &order_by=first_name
            &order=ASC
            &multi=true
        
2.     description: add additional field to users table
       url: base_url/personal-details/store-additional
       method: POST 
       params: "user_id" AND ("currently_seeking_opportunities" OR "other_jobs" OR "resume_link" OR "file" OR "image_id" OR "jke_note" OR "available_for_job")
       data: "user_id" (verchar), "currently_seeking_opportunities" (integer), "other_jobs" (text), "resume_link" (varchar), "file" (pdf,doc,txt), "image_id" (varchar), "jke_note" (varchar), "available_for_job" (date). 
   
       format:{  
              "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
              "currently_seeking_opportunities":"0"          
              }
             {  
              "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
              "other_jobs":"other"          
             }
             {  
              "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
              "resume_link":"resume_link"          
             }
              {  
               "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
               "image_id":"image_id"          
              }
               {  
                "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
                "jke_note":"jke_note"          
               }
              {  
               "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
               "available_for_job":"12/09/2015"          
              }
             {  
              "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
              "source":"source"          
             }
            {  
             "user_id":"e780dd41-55ad-49fc-9426-4a7b5e7d4717",
             "rating":"rating"          
            }
             form data
               "user_id" = "e780dd41-55ad-49fc-9426-4a7b5e7d4717"
               "file" = "file"
               
3. description:change user password
       url: base_url/users/change-password
       method: POST    
       params: user_id, old_password, password, password_confirmation. All fields are required.
       format:
           {
             "user_id":"5fb8f449-e842-44f9-9081-73b832168f0b",
             "old_password":"testtest",
             "password":"testtest111",
             "password_confirmation":"testtest111"
           }
#Available Routes - Upload profile picture/upload resume
1.  description: Checking whether all the chunk are uploaded
    url: base_url/users/upload
    method: GET

2.  description: Upload profile picture/upload resume
    url: base_url/users/upload
    method: POST
    params: query string "token" (varchar), query string "upload" (values: media, file)
            "Token" and "upload" are required.
    format: base_url/users/upload?user_id=371be5cc-931c-46d1-a49e-f3d91de0eae3&upload=media
    
3.  description: Delete profile picture.
    url: base_url/deleteProfile/{userId}
    method: DELETE 
    
4.  description: Delete resume.
    url: base_url/deleteResume/{userId}
    method: DELETE 
    
#Available Routes - Forgot password
1. description: Forgot password.
   url: base_url/forgot-password
   method: POST
   params: email (varchar).
   
2. description: Reset password.
   url: base_url/reset-password
   method: POST
   params: forgot_token (varchar), password (varchar), password_confirmation (varchar). 
   
3. description: Valid forgot token.
   url: base_url/valid-forgot-token
   method: POST
   params: forgot_token (varchar).
   
#Available Routes - Dashboard
1. description: Return all seeking job positions.
   url: base_url/seeking-job-positions
   method: GET
   
2. description: Return number of all active candidates and number of candidates who seeking job.
  url: base_url/total-active-candidates
  method: GET
  
3. description: Return recently added candidates.
    url: base_url/recently-added-candidates
    method: GET
    additional: query string "perPage" for output number of users per page.
    
4. description: Return number of all projects.
   url: base_url/number-of-projects
   method: GET

5.  description: Return recent activity of inspectors.
    url: base_url/recent-activity-view-all
    params: perPage (If not isset perPage showing only 15 records by default), user_id (Show recent activity for user by user_id), show=all 
    method: GET
    description: type 1 (is recently created), type 2 (is recently updated), type 3 (recently logged in system),
                type 4 (Assigned to Project), type 5 (Removed from Project), type 6 (Hired in Team), type 7 (Removed from Team)
                IF isset (type=4 OR type=5 OR type=6 OR type=7) then exist variable project_team_name (name of project/team)
    response: 
    {
        "total": 45,
        "per_page": "15",
        "current_page": 1,
        "last_page": 3,
        "next_page_url": "http://local.lms-api-jke:8080/recent-activity-view-all/?perPage=15&page=2",
        "prev_page_url": null,
        "from": 1,
        "to": 15,
        "data": [
            {
            "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
            "first_name": "Jean1",
            "last_name": "Beer",
            "date": "2015-12-09 10:19:07",
            "type": 1
            },
            {
            "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
            "first_name": "Jean1",
            "last_name": "Beer",
            "date": "2015-12-09 10:19:07",
            "type": 2
            },
            {
            "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
            "first_name": "Jean1",
            "last_name": "Beer",
            "date": "2015-12-09 10:19:07",
            "type": 3
            }
         ]
        }
   
6.  THIS IS NOT ACTIVE !!!
    description: Return recent created inspectors.
    url: base_url/recent-created?perPage=10 - If not isset perPage showing only 15 records by default.
    method: GET
    response: 
     {
       "data": [
         {
           "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
           "first_name": "Jean1",
           "last_name": "Beer",
           "date": "2015-12-07 13:26:35" 
         },
         {
           "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
           "first_name": "ccc",
           "last_name": "ccc",
           "date": "2015-12-07 13:24:09"
         
         },
         {
           "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
           "first_name": "ccc",
           "last_name": "ccc",
           "date": "2015-12-07 13:24:06"           
         }
         ]
     }
7. 
THIS IS NOT ACTIVE !!!
description: Return recent updated inspectors.
url: base_url/recent-updated?perPage=10 - If not isset perPage showing only 15 records by default.
method: GET
   response: 
   {
     "data": [
       {
         "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
         "first_name": "Jean1",
         "last_name": "Beer",
         "date": "2015-12-07 13:26:35"        
       },
       {
         "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
         "first_name": "ccc",
         "last_name": "ccc",
         "date": "2015-12-07 13:24:09"        
       },
       {
         "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
         "first_name": "ccc",
         "last_name": "ccc",
         "date": "2015-12-07 13:24:06"         
       }
       ]
   }

8. 
THIS IS NOT ACTIVE !!!
description: Return recent logged inspectors.
url: base_url/recent-logged?perPage=10 - If not isset perPage showing only 15 records by default.
method: GET
   response: 
   {
     "data": [
       {
         "id": "08ac5c3c-6dec-4989-afb1-2851e3b6b980",
         "first_name": "Jean1",
         "last_name": "Beer",
         "date": "2015-12-07 13:26:35"
         
       },
       {
         "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
         "first_name": "ccc",
         "last_name": "ccc",
         "date": "2015-12-07 13:24:09"         
       },
       {
         "id": "fb552293-3208-4a95-8e8d-3b74d6d2b3e1",
         "first_name": "ccc",
         "last_name": "ccc",
         "date": "2015-12-07 13:24:06"        
       }
       ]
   }
9. 
   description: Return recent project/team activity.
   url: base_url/recent-project-team-activity/user_id - If not isset perPage showing only 15 records by default.
   response params: activity_type (value 4 means Assigned to Project, value 5 means Removed from Project, 
   value 6 means Hired in Team, value 7 means Removed from Team)
   method: GET   
   response: 
    {
      "total": 2,
      "per_page": "15",
      "current_page": 1,
      "last_page": 1,
      "next_page_url": null,
      "prev_page_url": null,
      "from": 1,
      "to": 2,
      "data": [
        {
          "user_id": "3e339dad-1472-44b2-89d0-a4836709d5a0",
          "activity_type": 5,
          "project_team_name": "Dr. Daphne Prosacco",
          "created_at": "2016-01-13 14:43:04"
        },
        {
          "user_id": "3e339dad-1472-44b2-89d0-a4836709d5a0",
          "activity_type": 4,
          "project_team_name": "Dr. Daphne Prosacco",
          "created_at": "2016-01-13 14:42:53"
        }
      ]
    }
#Available Routes - Contact us
    url: base_url/contact-us
    method: POST
    params: "title", "body"
    format:{  
          "title":"title",
          "body":"body"          
          }
#Available Routes - Companies
1.  description: list/search companies
    url: base_url/companies
    additional: query string "show" with value "all" to show all company names without paginate.
    method: GET
    params: company_name, company_email, order_by (values: company_name, company_email, created_at, company_email, phone_number), order (values: ASC, DESC).
    Example:
            base_url/companies?page=1&company_name=company_name&company_email=company_email&order_by=company_name&order=ASC           

2. description: create new companies
   url: base_url/companies
   method: POST
   params: company_name, company_email, street_address, city, zip, state, country, web_site, notes.
   data: company_name is required and unique.
   format: {
               "company_name":"company_name",
               "company_email":"company_email",
               "street_address":"street_address",
               "city":"city",
               "zip":"zip",
               "state":"state",
               "country":"country",
               "web_site":"web_site",
               "notes":"notes"
           }
           
3.  description: get selected company by id
    url: base_url/companies/{id}
    method: GET
    
4.  description: edit existing company
    url: base_url/companies/{id}
    method: POST
    params: company_name, company_email, street_address, city, zip, state, country, web_site, notes.
    data: company_name is required and unique.
    format: {
               "company_name":"company_name",
               "company_email":"company_email",
               "phone_number":"phone_number",
               "street_address":"street_address",
               "city":"city",
               "zip":"zip",
               "state":"state",
               "country":"country",
               "web_site":"web_site",
               "notes":"notes"
           }
           
5. description: delete company
   url: base_url/companies/{id}
   method: DELETE
   
6.  description: Checking whether all the chunk are uploaded
    url: base_url/companies/upload
    method: GET

7.  description: Upload logo.
    url: base_url/companies/upload
    method: POST
    params: query string "company" (company_id)
            "company" are required.
    format: base_url/companies/upload?company=das32fds23_3434r_fds3
    
8.  description: Delete logo.
    url: base_url/companies/delete-logo/{id}
    params: id (company_id)
    method: DELETE
    
#Available Routes - Projects

1.  description: list/search projects
    url: base_url/projects
    method: GET
    params: project_name, company_name, order_by (values: project_name, company_name, created_at, start_date, end_date), 
    order (values: ASC, DESC).
        Example:
                base_url/projects?page=1&project_name=project_name&company_name=company_name&order_by=created_at&order=DESC 
                  
2. description: create new project
   url: base_url/projects
   method: POST
   data: project_name, owner, company_id, start_date, end_date are required.
   project_status (1/0 (yes/no))
   format: {
            "project_name":"project_name",
            "date_report_completed":"2015-10-27",
            "owner":"Owner",
            "company_id":"company_id",
            "project_status":"0",
            "phase_name":"phase_name",
            "service_level":"service_level",
            "street_address":"street_address",
            "city":"city",
            "zip":"zip",
            "state":"state",
            "country":"country",
            "start_date":"2015-10-27",
            "end_date":"2015-10-28"
           }
           
3.  description: get selected project by id
    url: base_url/projects/{projectId}
    method: GET
               
4.  description: edit existing project
    url: base_url/projects/{projectId}
    method: POST   
    data: id, project_name, owner, company_id, start_date, end_date are required.
    format: {
            "id": "project id",
            "project_name":"project_name",
            "date_report_completed":"2015-10-27",
            "owner":"Owner",
            "company_id":"company_id",
            "project_status":"0",
            "phase_name":"phase_name",
            "service_level":"service_level",
            "street_address":"street_address",
            "city":"city",
            "zip":"zip",
            "state":"state",
            "country":"country",
            "start_date":"2015-10-27",
            "end_date":"2015-10-28"
           }
5. description: delete project
   url: base_url/projects/{projectId}
   method: DELETE
   
6. description: store additional fields for projects
   url: base_url/projects/additional-fields{projectId}
   method: POST
   params: id AND ('id' OR 'critical_skills' OR 'uniform' OR 'audit' OR 'mentor' OR 'sop_training_test' OR 'oq_required' OR 'drug_test' 
                    OR 'safety_training_test' OR 'envir_training_test' OR 'field_tablet' OR 'software_forms' OR 'how_ot_handled_admin' OR 'per_diem_admin' 
                    OR 'electronics' OR 'truck' OR 'mileage_admin' OR 'day_rate' OR 'per_diem' OR 'sales_tax_required')
   drug_test (enum with values: 'None', 'DOT', 'Standard'. Default is 'None')               
   format: {
           "id": "project id",
           "some of the defined fields from array":"values"
          }
              
7. description: Get all activated projects
   url: base_url/projects/activated
   method: GET
           
8. description: Staff needed (team) list all for project for project id
   url: base_url/projects/staff/{projectId}
   method: GET
   
9. description: Staff needed (team) create new for project
   url: base_url/projects/staff
   method: POST
   data: desired_job_id, project_id, quantity are required.
   format: {
           "desired_job_id":"1",
           "project_id":"926fe1a2-78b9-461d-b3d9-0eea4b3b04b5",
           "quantity":"1",
           "quality":"4",
           "start":"2015-10-28",
           "finish":"2015-10-29",
           "note":"note",
           "day_rate":"3",
           "days_wk":"5",
           "holidays":"4"              
          }
10. description: Staff needed (team) edit for project
   url: base_url/projects/staff/{projectId}
   method: POST
   data: id (id from pivot table), quantity are required.
   format: {
          "id":"1",    
          "desired_job_id":"2",
          "project_id":"project_id",
          "quantity":"1",
          "quality":"4",
          "start":"2015-10-28",
          "finish":"2015-10-29",
          "note":"note",
          "day_rate":"3",
          "days_wk":"5",
          "holidays":"4"              
         }
         
11.  description: delete Staff needed (team) by userId and experienceId (id from pivot table)
    url: base_url/projects/staff/{projectId}/{staffId}
    method: DELETE
    
12. description: Add candidates for project.
   url: base_url/projects/candidates
   method: POST
   data: user_id, desired_job_project_id.
   format: {
           "user_id":"6bd45477-a60d-4869-ad5f-85d7009fc586",
           "desired_job_project_id":"0a966d3a-216f-4582-aeb9-06ea713116a8"             
           }
           
13. description: List all candidates for project for staff Id.
   url: base_url/projects/candidates/{staffId}
   method: GET
   
14.description: Delete candidate from table for staffId (id from pivot table) and userId
   url: base_url/projects/candidates/{staffId}/{userId}
   method: DELETE
   
15. description: Get projects by company id.
    url: base_url/projects/by-company/{companyId}
    params: order_by (values: project_name, company_name, start_date, end_date), order (values: ASC, DESC), paginate (value: true)
    method: GET
    
16. description: Get candidates in a project with team and job position info
   url: base_url/projects/candidates/project/{projectId}
   method: GET
   params: first_last_name, email, desired_job (desired job id), job_title, seeking_job (value: yes/no), 
   certificate_type (id of Certificate), certificate_name, certificate_agency, certificate_expiration_date
   order_by (first_last_name, Technical, Critical, Assessment, created_at, ),order (values: ASC, DESC), hired(value: yes, no, any), status (Hired, Interviewed, Not qualified, Pending, Any)
   extra: query parameter multi with values true and false. multi=true is preventing multiple assigns
   Example:
          base_url/projects/candidates/project/{projectId}?first_last_name=Admin Master            
          &email=admin@domain.ltd
          &desired_job=1
          &job_title=Developer
          &seeking_job=no
          &certificate_type=1
          &certificate_name=certificate_name
          &certificate_agency=certificate_agency
          &certificate_expiration_date=some_date
          &order_by=first_name
          &order=ASC
          &hired=any
          &status=Hired
          &multi=true

#Available Routes - Teams

1.  description: get all teams
    url: base_url/teams
    method: GET
    params: team_name, project_name, company_name, order_by (team_name, project_name, company_name), order (ASC, DESC) 
    variables: company_id query string is used for returning teams by company. Without it all are returned.
    Example:
    base_url/teams?page=1
    &team_name=test
    &project_name=test
    &company_name
    &order_by=team_name
    &order=DESC
    
2. description: create new team
   url: base_url/teams
   method: POST
   data: name (required), project_id (required).

3.  description: get selected team by id
    url: base_url/teams/{teamId}
    method: GET

4.  description: edit existing team
    url: base_url/teams/{teamId}
    method: POST
    data: name (required), project_id (required).

5. description: delete team
   url: base_url/teams/{teamId}
   method: DELETE


6. description: Assign user to team
   url: base_url/teams/{teamId}/assign
   method: POST
   data: user (required) and status (values: Hired, Interviewed, Not qualified) (required)

7. description: Remove assigned user from team
   url: base_url/teams/{teamId}/revoke
   method: POST
   data: user (required)

#Available Routes - Innermetrix

1. description: create new innermetrix entry
   url: base_url/innermetrix
   method: POST
   data: user_id (required), decisive, interactive, stabilizing, cautious, aesthetic,
         economic, individualistic, political, altruist, regulatory, theoretical, getting_results,
         interpersonal_skills, making_decisions, work_ethic

2.  description: get innermetrix for user by id
    url: base_url/innermetrix/{userId}
    method: GET

3.  description: edit innermetrix for user by id
    url: base_url/innermetrix/{userId}
    method: POST
    data: decisive, interactive, stabilizing, cautious, aesthetic,
          economic, individualistic, political, altruist, regulatory, theoretical, getting_results,
          interpersonal_skills, making_decisions, work_ethic

4. description: delete innermetrix for user by id
   url: base_url/innermetrix/{userId}
   method: DELETE
   
#Available Routes - Clients
   
1.  description: list/search clients.
    url: base_url/clients
    method: GET    
    params: first_last_name, email, company (company_id), order_by(values: first_last_name, email, created_at), order (values: ASC, DESC)
    Example:
            base_url/clients?page=1&first_last_name=Admin Master            
            &email=admin@domain.ltd
            &company=1            
            &order_by=first_name
            &order=ASC
            
2. description: create new client entry
   url: base_url/clients
   method: POST
   data: first_name (required), last_name (required), email (required), role_id (required), password (required),
          password_confirmation (required), company_id (required).
   format: 
          {
           "first_name":"first_name",
           "last_name":"last_name",
           "email":"email@email.com",
           "role_id":2,
           "password":"testtest",
           "password_confirmation":"testtest",
           "company_id": "6d0db1d4-f857-4a77-979e-cb899f4cbe3e",
           "jke_note": "jke_note"
           }
   response format:
           {
             "data": {
               "user_id": "77060337-59a2-493f-8346-cfcf522f455a",
               "company_id": "6d0db1d4-f857-4a77-979e-cb899f4cbe3e",
               "first_name": "first_name",
               "last_name": "last_name",
               "email": "email@email.com",
               "image_id": null,
               "image_url": null,
               "jke_note": "jke_note",
               "role": {
                 "role_id": "2",
                 "role_name": "Client"
               }
             },
             "code": 201
           }
           
3. 
  description: get clients by id (id from pivot table users_companies)
  url: base_url/clients/{id}
  method: GET
  response format:
      {
        "data": {
          "id": "b2c6d07f-683f-4a8f-b2e6-b13f04457fb8",
          "user_id": "05b1fb0b-31f5-498e-bd8d-16c1973e53d8",
          "company_id": "ebe27e9b-f5f5-444a-b9db-27df1e31bd8e",
          "company_name": "company_name",
          "first_name": "first_name",
          "last_name": "last_name",
          "email": "email@email.com",
          "image_id": null,
          "image_url": null,
          "jke_note": "jke_note",
          "role": {
            "role_id": "2",
            "role_name": "Client"
          },
          "created_at": "2015-11-18T09:41:37+00:00"
        },
        "code": 200
      }
      
4. 
  description: update clients by id (id from pivot table users_companies)
  url: base_url/clients/{id}
  method: POST
  data: first_name (required), last_name (required), email (required), password (if isset then must be equal with password),
        password_confirmation (if isset then must be equal with password), company_id (required).
 format: 
        {
         "first_name":"first_name",
         "last_name":"last_name",
         "email":"email@email.com",         
         "password":"testtest",
         "password_confirmation":"testtest",
         "company_id": "6d0db1d4-f857-4a77-979e-cb899f4cbe3e",
         "jke_note": "jke_note"
         }
         
response format:
        {
          "data": {
            "id": "b2c6d07f-683f-4a8f-b2e6-b13f04457fb8",
            "user_id": "59ac336b-f6c1-443c-bb40-7716ab24bf5d",
            "company_id": "dfaf1186-6ef9-484d-ae44-35ee3d7b26cd",
            "company_name": "Balistreri-Mueller2",
            "first_name": "first_name",
            "last_name": "last_name",
            "email": "email@email.com",
            "image_id": null,
            "image_url": null,
            "jke_note": "jke_note",
            "role": {
              "role_id": "2",
              "role_name": "Client"
            },
            "created_at": "2015-11-18T09:42:20+00:00"
          },
          "code": 200
        }

5.  description: Delete client by id (id from pivot table users_companies).
    url: base_url/clients/{id}
    method: DELETE

#Available Routes - Scoring Templates

1. description: create new scoring template
   url: base_url/scoring-templates
   method: POST
   data: id, desired_job_id, work_experience_weight, certificates_weight, auditor_weight, disc_weight, values_weight, attributes_weight, 
         work_experience_criteria_level1, certificates_criteria_level1, auditor_criteria_level1, disc_criteria_level1,  'values_criteria_level1, attributes_criteria_level1
         work_experience_criteria_level2, certificates_criteria_level2, auditor_criteria_level2, disc_criteria_level2,  'values_criteria_level2, attributes_criteria_level2
         work_experience_criteria_level3, certificates_criteria_level3, auditor_criteria_level3, disc_criteria_level3,  'values_criteria_level3, attributes_criteria_level3
         work_experience_criteria_level4, certificates_criteria_level4, auditor_criteria_level4, disc_criteria_level4,  'values_criteria_level4, attributes_criteria_level4
         work_experience_criteria_level5, certificates_criteria_level5, auditor_criteria_level5, disc_criteria_level5,  'values_criteria_level5, attributes_criteria_level5

2.  description: get scoring template by id
    url: base_url/scoring-templates/{templateId}
    method: GET

3.  description: edit scoring template by id
    url: base_url/scoring-templates/{templateId}
    method: POST
    data: id, desired_job_id, work_experience_weight, certificates_weight, auditor_weight, disc_weight, values_weight, attributes_weight, 
          work_experience_criteria_level1, certificates_criteria_level1, auditor_criteria_level1, disc_criteria_level1,  'values_criteria_level1, attributes_criteria_level1
          work_experience_criteria_level2, certificates_criteria_level2, auditor_criteria_level2, disc_criteria_level2,  'values_criteria_level2, attributes_criteria_level2
          work_experience_criteria_level3, certificates_criteria_level3, auditor_criteria_level3, disc_criteria_level3,  'values_criteria_level3, attributes_criteria_level3
          work_experience_criteria_level4, certificates_criteria_level4, auditor_criteria_level4, disc_criteria_level4,  'values_criteria_level4, attributes_criteria_level4
          work_experience_criteria_level5, certificates_criteria_level5, auditor_criteria_level5, disc_criteria_level5,  'values_criteria_level5, attributes_criteria_level5

4. description: delete scoring template by id
   url: base_url/scoring-templates/{templateId}
   method: DELETE
   
5. description: restore deleted scoring template by id
  url: base_url/scoring-templates/{templateId}
  method: PATCH
  
6.  description: list/search templates.
    url: base_url/scoring-templates
    method: GET    
    params: name (filter by name), order_by, order_dir (asc, desc), order_by (name, created_at)
    Example:
            base_url/scoring-templates?page=1&order_dir=desc            
            &order_by=name
            &name=template  

### DISABLED AND NOT USED AT THE MOMENT ###            
7.  description: assign scoring template to desired job in project
    params: type should be one of the following 'Technical', 'Critical' or 'Assessment'
    url: base_url/scoring-templates/assign/{templateId}/desired-job/{desiredJobProjectId}/{type}
    method: GET
                
8.  description: remove assigned template from desired job in project
    params: type should be one of the following 'Technical', 'Critical' or 'Assessment'
    url: base_url/scoring-templates/revoke/{templateId}/desired-job/{desiredJobProjectId}/{type}
    method: GET
### END OF DISABLED AND NOT USED AT THE MOMENT ### 
                     
#Available Routes - Scorings

1. description: get all scorings for all users
   url: base_url/scorings
   method: GET
   
2. description: create/update scoring for user.
   url: base_url/scorings
   params: user_id AND technical_skills  AND critical_skills AND assessment (integer or decimal)
   method: POST
   format: 
  {
      "user_id": "9664c4cb-857f-4ef6-9b19-5a7613246ff8",
      "technical_skills":"20",
      "critical_skills":"20",
      "assessment":"20"
  }

    
3.  description: get scoring by userId
    url: base_url/scorings/{userId}
    method: GET
    
4.  description: delete scoring by userId
    url: base_url/scorings/{userId}
    method: DELETE
    
5.  description: get automatic scoring by userId
    url: base_url/scorings/automatic/{userId}
    query string params: desired_job_id
    method: GET
    
# Available Routes - Reference qualifications

1.  description: reference qualifications for inspector-guest relation
    url: base_url/reference-qualifications/{userId}
    query string params: reference_email (required only if sent token is not from admin account)
    method: GET
    
2. description: create reference qualifications for inspector-guest relation
   url: base_url/reference-qualifications
   params: guests_users_id, qualification_id, rating, reference_email (required only if sent token is not from admin account)
   method: POST
    
3.  description: edit reference qualifications for inspector-guest relation
    url: base_url/reference-qualifications/{reference_id}
    params: reference_id, qualification_id, rating, reference_email (required only if sent token is not from admin account)
    method: POST
    
4.  description: delete reference qualifications for inspector-guest relation
    url: base_url/reference-qualifications/{reference_id}/{qualification_id}
    query string params: reference_email (required only if sent token is not from admin account)
    method: DELETE

5.  description: get note for reference
    url: base_url/reference-qualifications/note/{referenceId}
    query string params: reference_email (required only if sent token is not from admin account)
    method: GET
    
6. description: create/update note
   url: base_url/reference-qualifications/note
   params: reference_id, note, reference_email (required only if sent token is not from admin account)
   method: POST
 