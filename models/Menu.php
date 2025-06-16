<?php
require_once __DIR__ . '/../includes/Database.php';

class Menu {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Get all menus
    public function getAll() {
        try {
            // Debug log the SQL query
            error_log("Executing getAll query");
            
            $sql = "SELECT * FROM menus ORDER BY start_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug log raw data from database
            error_log("Raw menus data from database: " . print_r($menus, true));
            
            if (empty($menus)) {
                error_log("No menus found in database");
                return [];
            }
            
            // Format each menu for display
            $formattedMenus = [];
            foreach ($menus as $menu) {
                try {
                    error_log("Processing menu ID: " . $menu['id']);
                    error_log("Menu data before formatting: " . print_r($menu, true));
                    
                    $formatted = $this->formatMenuForDisplay($menu);
                    if ($formatted) {
                        error_log("Menu after formatting: " . print_r($formatted, true));
                        $formattedMenus[] = $formatted;
                    } else {
                        error_log("Menu formatting returned null for ID: " . $menu['id']);
                    }
                } catch (Exception $e) {
                    error_log("Error formatting menu ID {$menu['id']}: " . $e->getMessage());
                    continue;
                }
            }
            
            // Debug log final formatted menus
            error_log("Final formatted menus: " . print_r($formattedMenus, true));
            
            return $formattedMenus;
        } catch (Exception $e) {
            error_log("Error getting all menus: " . $e->getMessage());
            return [];
        }
    }

    // Get menu by ID
    public function getById($id) {
        try {
            $sql = "SELECT * FROM menus WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting menu by id: " . $e->getMessage());
            return null;
        }
    }

    // Create new menu
    public function create($data) {
        try {
            // Debug log
            error_log("Creating menu with data: " . print_r($data, true));
            
            $sql = "INSERT INTO menus (start_date, end_date, 
                monday_breakfast, monday_lunch, monday_snack,
                tuesday_breakfast, tuesday_lunch, tuesday_snack,
                wednesday_breakfast, wednesday_lunch, wednesday_snack,
                thursday_breakfast, thursday_lunch, thursday_snack,
                friday_breakfast, friday_lunch, friday_snack) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['start_date'],
                $data['end_date'],
                $data['monday_breakfast'] ?? null,
                $data['monday_lunch'] ?? null,
                $data['monday_snack'] ?? null,
                $data['tuesday_breakfast'] ?? null,
                $data['tuesday_lunch'] ?? null,
                $data['tuesday_snack'] ?? null,
                $data['wednesday_breakfast'] ?? null,
                $data['wednesday_lunch'] ?? null,
                $data['wednesday_snack'] ?? null,
                $data['thursday_breakfast'] ?? null,
                $data['thursday_lunch'] ?? null,
                $data['thursday_snack'] ?? null,
                $data['friday_breakfast'] ?? null,
                $data['friday_lunch'] ?? null,
                $data['friday_snack'] ?? null
            ];
            
            // Debug log
            error_log("SQL parameters: " . print_r($params, true));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $menuId = $this->db->lastInsertId();
            
            // Debug log
            error_log("Created menu with ID: " . $menuId);
            
            return $menuId;
        } catch (Exception $e) {
            error_log("Error creating menu: " . $e->getMessage());
            return false;
        }
    }

    // Update menu
    public function update($id, $data) {
        try {
            $sql = "UPDATE menus SET 
                start_date = ?, end_date = ?,
                monday_breakfast = ?, monday_lunch = ?, monday_snack = ?,
                tuesday_breakfast = ?, tuesday_lunch = ?, tuesday_snack = ?,
                wednesday_breakfast = ?, wednesday_lunch = ?, wednesday_snack = ?,
                thursday_breakfast = ?, thursday_lunch = ?, thursday_snack = ?,
                friday_breakfast = ?, friday_lunch = ?, friday_snack = ?
                WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['start_date'],
                $data['end_date'],
                $data['monday_breakfast'] ?? null,
                $data['monday_lunch'] ?? null,
                $data['monday_snack'] ?? null,
                $data['tuesday_breakfast'] ?? null,
                $data['tuesday_lunch'] ?? null,
                $data['tuesday_snack'] ?? null,
                $data['wednesday_breakfast'] ?? null,
                $data['wednesday_lunch'] ?? null,
                $data['wednesday_snack'] ?? null,
                $data['thursday_breakfast'] ?? null,
                $data['thursday_lunch'] ?? null,
                $data['thursday_snack'] ?? null,
                $data['friday_breakfast'] ?? null,
                $data['friday_lunch'] ?? null,
                $data['friday_snack'] ?? null,
                $id
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error updating menu: " . $e->getMessage());
            return false;
        }
    }

    // Delete menu
    public function delete($id) {
        try {
            $sql = "DELETE FROM menus WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return true;
        } catch (Exception $e) {
            error_log("Error deleting menu: " . $e->getMessage());
            return false;
        }
    }

    // Get menu by date range
    public function getByDateRange($startDate, $endDate) {
        try {
            $sql = "SELECT * FROM menus WHERE start_date = ? AND end_date = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$startDate, $endDate]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting menu by date range: " . $e->getMessage());
            return null;
        }
    }

    // Get current week menu
    public function getCurrentWeekMenu() {
        try {
            $today = new DateTime();
            $dayOfWeek = $today->format('N'); // 1 (Monday) to 7 (Sunday)
            
            // If it's weekend, get next week's menu
            if ($dayOfWeek >= 5) {
                $today->modify('next monday');
            }
            
            $startDate = clone $today;
            $startDate->modify('monday this week');
            $endDate = clone $startDate;
            $endDate->modify('friday this week');
            
            $menu = $this->getByDateRange($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
            return $menu ? $this->formatMenuData($menu) : null;
        } catch (Exception $e) {
            error_log("Error getting current week menu: " . $e->getMessage());
            return null;
        }
    }

    // Get current week info
    public function getCurrentWeekInfo() {
        try {
            $today = new DateTime();
            $dayOfWeek = $today->format('N'); // 1 (Monday) to 7 (Sunday)
            $isNextWeek = false;
            
            // If it's weekend, get next week's info
            if ($dayOfWeek >= 5) {
                $today->modify('next monday');
                $isNextWeek = true;
            }
            
            $startDate = clone $today;
            $startDate->modify('monday this week');
            $endDate = clone $startDate;
            $endDate->modify('friday this week');
            
            return [
                'week' => (int)$startDate->format('W'),
                'year' => (int)$startDate->format('Y'),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'is_next_week' => $isNextWeek
            ];
        } catch (Exception $e) {
            error_log("Error getting current week info: " . $e->getMessage());
            return null;
        }
    }

    // Format menu data for display
    private function formatMenuData($menu) {
        return [
            'monday' => [
                'breakfast' => $menu['monday_breakfast'],
                'lunch' => $menu['monday_lunch'],
                'snack' => $menu['monday_snack']
            ],
            'tuesday' => [
                'breakfast' => $menu['tuesday_breakfast'],
                'lunch' => $menu['tuesday_lunch'],
                'snack' => $menu['tuesday_snack']
            ],
            'wednesday' => [
                'breakfast' => $menu['wednesday_breakfast'],
                'lunch' => $menu['wednesday_lunch'],
                'snack' => $menu['wednesday_snack']
            ],
            'thursday' => [
                'breakfast' => $menu['thursday_breakfast'],
                'lunch' => $menu['thursday_lunch'],
                'snack' => $menu['thursday_snack']
            ],
            'friday' => [
                'breakfast' => $menu['friday_breakfast'],
                'lunch' => $menu['friday_lunch'],
                'snack' => $menu['friday_snack']
            ]
        ];
    }

    // Format menu data for display
    private function formatMenuForDisplay($menu) {
        try {
            if (empty($menu['start_date']) || empty($menu['end_date'])) {
                error_log("Invalid menu data - missing dates: " . print_r($menu, true));
                return null;
            }

            $startDate = new DateTime($menu['start_date']);
            $endDate = new DateTime($menu['end_date']);
            
            // Debug log input menu data
            error_log("Formatting menu data: " . print_r($menu, true));
            
            $formatted = [
                'id' => $menu['id'],
                'week_label' => 'Tuần ' . $startDate->format('W') . 
                              ' (' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y') . ')',
                'start_date' => $menu['start_date'],
                'end_date' => $menu['end_date'],
                'monday' => [
                    'breakfast' => $menu['monday_breakfast'] ?? '',
                    'lunch' => $menu['monday_lunch'] ?? '',
                    'snack' => $menu['monday_snack'] ?? ''
                ],
                'tuesday' => [
                    'breakfast' => $menu['tuesday_breakfast'] ?? '',
                    'lunch' => $menu['tuesday_lunch'] ?? '',
                    'snack' => $menu['tuesday_snack'] ?? ''
                ],
                'wednesday' => [
                    'breakfast' => $menu['wednesday_breakfast'] ?? '',
                    'lunch' => $menu['wednesday_lunch'] ?? '',
                    'snack' => $menu['wednesday_snack'] ?? ''
                ],
                'thursday' => [
                    'breakfast' => $menu['thursday_breakfast'] ?? '',
                    'lunch' => $menu['thursday_lunch'] ?? '',
                    'snack' => $menu['thursday_snack'] ?? ''
                ],
                'friday' => [
                    'breakfast' => $menu['friday_breakfast'] ?? '',
                    'lunch' => $menu['friday_lunch'] ?? '',
                    'snack' => $menu['friday_snack'] ?? ''
                ]
            ];
            
            // Debug log formatted output
            error_log("Formatted menu output: " . print_r($formatted, true));
            
            return $formatted;
        } catch (Exception $e) {
            error_log("Error formatting menu: " . $e->getMessage());
            return null;
        }
    }

    // Format meals for a specific day
    private function formatDayMeals($menu, $day) {
        $meals = [];
        if (!empty($menu[$day . '_breakfast'])) {
            $meals[] = 'Breakfast: ' . $menu[$day . '_breakfast'];
        }
        if (!empty($menu[$day . '_lunch'])) {
            $meals[] = 'Lunch: ' . $menu[$day . '_lunch'];
        }
        if (!empty($menu[$day . '_snack'])) {
            $meals[] = 'Snack: ' . $menu[$day . '_snack'];
        }
        return implode('<br>', $meals);
    }
}
