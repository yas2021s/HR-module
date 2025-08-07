# HR Payroll API Documentation

This directory contains the Swagger/OpenAPI documentation for the HR Payroll Management System.

## Files

- `hr-payroll-api.yaml` - The main OpenAPI 3.0 specification file containing all API endpoints
- `../swagger-ui/index.html` - Swagger UI interface to view and test the API documentation

## API Overview

The HR Payroll API provides comprehensive endpoints for managing payroll operations including:

### Payroll Management
- Get payroll overview and details
- Define and edit payroll for specific months
- Submit and commit payroll
- Manage payroll records (CRUD operations)

### Pay Structure
- Retrieve pay structure information
- Get slab types for pay calculations
- Manage payheads and their configurations

### Employee Payroll
- Get employee-specific payroll data
- Retrieve basic salary information
- Get TDS (Tax Deducted at Source) information

### Salary Booking
- Book regular salary for employees
- Book advance salary
- Manage salary booking workflows

### PEPF (Public Enterprise Provident Fund)
- Generate and manage PEPF calculations
- Submit PEPF data
- View PEPF details and overview

### PBVI (Payroll Bank Voucher Issue)
- Generate PBVI for payroll processing
- Edit and submit PBVI data
- View PBVI details and overview

## Accessing the Documentation

### Option 1: Direct HTML Access
Navigate to: `http://your-domain/swagger-ui/index.html`

### Option 2: Via Web Server
If you have a web server running, you can access the documentation at:
- Development: `http://localhost:8080/swagger-ui/index.html`
- Production: `https://your-domain.com/swagger-ui/index.html`

## API Authentication

The API uses JWT Bearer token authentication. Include the token in the Authorization header:

```
Authorization: Bearer <your-jwt-token>
```

## API Endpoints

### Base URL
- Development: `http://localhost:8080`
- Production: `https://api.company.com`

### Key Endpoints

#### Payroll Management
- `GET /payroll` - Get payroll overview
- `GET /payroll/{year}-{month}` - Get payroll details for specific month
- `GET /payroll/define/{year}-{month}` - Define payroll for new month
- `POST /payroll/submit` - Submit payroll for processing
- `POST /payroll/commit` - Commit payroll for final processing

#### Employee Operations
- `GET /payroll/employee` - Get employee payroll data
- `GET /payroll/basic/{employee_id}` - Get employee basic salary
- `GET /payroll/tds` - Get TDS information

#### PEPF Operations
- `GET /payroll/pepf` - Get PEPF overview
- `POST /payroll/generate-pepf` - Generate PEPF calculations
- `POST /payroll/submit-pepf` - Submit PEPF data
- `GET /payroll/view-pepf/{year}-{month}` - View PEPF details

#### PBVI Operations
- `GET /payroll/pbvi` - Get PBVI overview
- `POST /payroll/generate-pbvi` - Generate PBVI
- `POST /payroll/submit-pbvi` - Submit PBVI data
- `GET /payroll/view-pbvi/{year}-{month}` - View PBVI details

## Data Models

The API documentation includes comprehensive data models for:

- **Payroll**: Core payroll information
- **TempPayroll**: Temporary payroll data during processing
- **EmployeePayroll**: Employee-specific payroll details
- **PEPFOverview/PEPFDetails**: Provident fund information
- **PBVIOverview/PBVIDetails**: Bank voucher information
- **PayStructure**: Pay structure and payhead configurations
- **BasicSalary**: Employee basic salary information
- **TDSInfo**: Tax deduction information

## Error Responses

The API returns standard HTTP status codes:

- `200` - Success
- `400` - Bad Request (invalid data)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `500` - Internal Server Error

## Development

### Updating the API Documentation

1. Edit the `hr-payroll-api.yaml` file to add new endpoints or modify existing ones
2. Follow the OpenAPI 3.0 specification format
3. Test the documentation by refreshing the Swagger UI page

### Adding New Endpoints

When adding new endpoints to the PayrollController:

1. Add the endpoint definition to `hr-payroll-api.yaml`
2. Include proper request/response schemas
3. Add appropriate tags for categorization
4. Include authentication requirements
5. Document all parameters and response codes

### Schema Definitions

All data models are defined in the `components.schemas` section of the YAML file. When adding new models:

1. Define the schema with proper types and examples
2. Use `$ref` to reference schemas in endpoint definitions
3. Include validation rules where applicable
4. Provide meaningful examples

## Testing

You can test the API endpoints directly from the Swagger UI interface:

1. Open the Swagger UI page
2. Click on any endpoint to expand it
3. Click "Try it out" to test the endpoint
4. Fill in required parameters
5. Click "Execute" to make the request
6. View the response and response headers

## Security

- All endpoints require JWT authentication
- Sensitive data is properly secured
- API keys and tokens should be kept confidential
- Use HTTPS in production environments

## Support

For questions or issues with the API documentation:

1. Check the Swagger UI interface for endpoint details
2. Review the YAML file for complete specifications
3. Contact the development team for technical support

## Version History

- **v1.0.0** - Initial API documentation with comprehensive payroll endpoints 