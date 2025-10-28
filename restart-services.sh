#!/bin/bash

echo "🔄 Restarting Laravel Services..."
echo ""

# Navigate to backend directory
cd /Users/naveentehrpariya/Office/PropWeakth/backend

# Clear all Laravel caches
echo "1️⃣  Clearing Laravel caches..."
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo ""
echo "✅ Caches cleared!"
echo ""

# Kill existing PHP artisan serve processes
echo "2️⃣  Stopping existing Laravel server..."
pkill -f "php artisan serve" 2>/dev/null
pkill -f "php.*127.0.0.1:8000" 2>/dev/null
sleep 2

echo ""
echo "3️⃣  Stopping existing queue workers..."
pkill -f "php artisan queue:work" 2>/dev/null
sleep 2

echo ""
echo "✅ All services stopped!"
echo ""
echo "📝 Now you need to manually start:"
echo ""
echo "   Terminal 1 (Server):"
echo "   cd /Users/naveentehrpariya/Office/PropWeakth/backend"
echo "   php artisan serve --port=8000"
echo ""
echo "   Terminal 2 (Queue Worker):"
echo "   cd /Users/naveentehrpariya/Office/PropWeakth/backend"
echo "   php artisan queue:work --tries=3 --sleep=3"
echo ""
