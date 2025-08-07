#!/bin/bash

# HR Payroll API Swagger Setup Script
# This script helps set up and configure Swagger documentation for the HR Payroll API

echo "🚀 Setting up HR Payroll API Swagger Documentation..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "hr-payroll-api.yaml" ]; then
    print_error "Please run this script from the swagger directory"
    exit 1
fi

print_status "Checking file structure..."

# Check if required files exist
required_files=(
    "hr-payroll-api.yaml"
    "../swagger-ui/index.html"
    "config.json"
    "README.md"
)

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        print_success "✓ $file exists"
    else
        print_error "✗ $file missing"
    fi
done

print_status "Validating YAML syntax..."

# Check if yamllint is available for YAML validation
if command -v yamllint &> /dev/null; then
    if yamllint hr-payroll-api.yaml; then
        print_success "✓ YAML syntax is valid"
    else
        print_error "✗ YAML syntax errors found"
    fi
else
    print_warning "yamllint not found - skipping YAML validation"
fi

print_status "Checking web server access..."

# Test if the Swagger UI can be accessed
if command -v curl &> /dev/null; then
    # Try to access the Swagger UI (this is just a test, actual server might not be running)
    print_status "Testing Swagger UI accessibility..."
    if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/swagger-ui/index.html | grep -q "200\|404"; then
        print_success "✓ Web server is accessible"
    else
        print_warning "⚠ Web server might not be running or accessible"
    fi
else
    print_warning "curl not found - skipping web server test"
fi

print_status "Setting up permissions..."

# Set proper permissions for files
chmod 644 hr-payroll-api.yaml
chmod 644 config.json
chmod 644 README.md
chmod 644 ../swagger-ui/index.html

print_success "✓ File permissions set"

print_status "Creating access URLs..."

# Generate access URLs
echo ""
echo "📋 Access URLs:"
echo "================"
echo "Swagger UI: http://localhost:8080/swagger-ui/index.html"
echo "API Spec:   http://localhost:8080/swagger/hr-payroll-api.yaml"
echo "Config:     http://localhost:8080/swagger/config.json"
echo ""

print_status "Checking for PHP dependencies..."

# Check if PHP is available
if command -v php &> /dev/null; then
    print_success "✓ PHP is available"
    
    # Check if generate.php script works
    if php -l generate.php &> /dev/null; then
        print_success "✓ generate.php syntax is valid"
    else
        print_error "✗ generate.php has syntax errors"
    fi
else
    print_warning "⚠ PHP not found - some features may not work"
fi

print_status "Setting up development environment..."

# Create a simple test script
cat > test-api.sh << 'EOF'
#!/bin/bash
echo "🧪 Testing HR Payroll API Documentation..."

# Test if the YAML file is accessible
if curl -s http://localhost:8080/swagger/hr-payroll-api.yaml > /dev/null 2>&1; then
    echo "✓ API specification is accessible"
else
    echo "✗ API specification is not accessible"
fi

# Test if the Swagger UI is accessible
if curl -s http://localhost:8080/swagger-ui/index.html > /dev/null 2>&1; then
    echo "✓ Swagger UI is accessible"
else
    echo "✗ Swagger UI is not accessible"
fi

echo "🎉 API documentation setup complete!"
EOF

chmod +x test-api.sh
print_success "✓ Test script created: test-api.sh"

print_status "Creating documentation summary..."

# Create a summary of the API endpoints
cat > api-summary.md << 'EOF'
# HR Payroll API Summary

## Quick Start
1. Access Swagger UI: http://localhost:8080/swagger-ui/index.html
2. Explore the API endpoints
3. Test endpoints using the "Try it out" feature

## Main Endpoint Categories

### Payroll Management
- `GET /payroll` - Get payroll overview
- `GET /payroll/{year}-{month}` - Get payroll details
- `POST /payroll/submit` - Submit payroll
- `POST /payroll/commit` - Commit payroll

### Employee Operations
- `GET /payroll/employee` - Get employee payroll data
- `GET /payroll/basic/{employee_id}` - Get basic salary
- `GET /payroll/tds` - Get TDS information

### PEPF Operations
- `GET /payroll/pepf` - Get PEPF overview
- `POST /payroll/generate-pepf` - Generate PEPF
- `POST /payroll/submit-pepf` - Submit PEPF

### PBVI Operations
- `GET /payroll/pbvi` - Get PBVI overview
- `POST /payroll/generate-pbvi` - Generate PBVI
- `POST /payroll/submit-pbvi` - Submit PBVI

## Authentication
All endpoints require JWT Bearer token authentication.

## Testing
Run `./test-api.sh` to test the API documentation setup.
EOF

print_success "✓ API summary created: api-summary.md"

echo ""
print_success "🎉 HR Payroll API Swagger Documentation setup complete!"
echo ""
echo "📖 Next steps:"
echo "1. Start your web server"
echo "2. Navigate to: http://localhost:8080/swagger-ui/index.html"
echo "3. Explore the API documentation"
echo "4. Test endpoints using the interactive interface"
echo ""
echo "📚 Documentation files:"
echo "- API Spec: hr-payroll-api.yaml"
echo "- UI Interface: ../swagger-ui/index.html"
echo "- Configuration: config.json"
echo "- Setup Guide: README.md"
echo "- API Summary: api-summary.md"
echo "- Test Script: test-api.sh"
echo ""
print_status "Happy API documenting! 🚀" 