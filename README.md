# Magento 2 Admin Grid Using UI Component with Import Data via CSV

## Overview

This Magento 2 extension provides an admin grid using UI Components, allowing users to:

- Add new records to the grid.

- Import data using a CSV file.

- Download the same CSV file.

- Truncate all records from the grid.

## Features

- Fully functional admin grid built using Magento 2 UI Components.

- CSV import functionality for bulk data entry.

- CSV export functionality to download existing records.

- Truncate feature to delete all records at once.

- Compatible with Magento 2.x.

## Installation

1. Download or clone this repository.

2. Copy the module files to app/code/PinBlooms/MasterData.

3. Run the following commands in your Magento root directory:
   ```
   php bin/magento module:enable PinBlooms_MasterData
   php bin/magento setup:upgrade
   php bin/magento setup:di:compile
   php bin/magento setup:static-content:deploy -f
   php bin/magento cache:flush
   ```
## Configuration

After installation, navigate to Admin Panel MASTER DATA → MasterData to manage records, import/export CSV files, and truncate the grid.

## Usage

1. Add Record: Click the 'Add New Record' button and fill in the required fields.

2. Import CSV: Click 'Import CSV', select your file, and upload.

3. Download CSV: Click 'Download CSV' to export the existing data.

4. Truncate Grid: Click 'Truncate' to delete all records from the grid.

## CSV Format

Ensure your CSV file follows this structure:
```
column1,column2,column3
value1,value2,value3
value4,value5,value6
```
## Support

For any issues, feel free to open a GitHub issue or contact us.

## License

This module is released under the MIT License.
