import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Dimensions,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useDispatch } from 'react-redux';
import { logout } from '../app/reducers/authReducer';
import { ROUTES } from '../utils';
import Sidebar from '../components/Sidebar';
import CustomButton from '../components/CustomButton';

const { width } = Dimensions.get('window');
const isTablet = width >= 768;

const MyPetsScreen = () => {
  const navigation = useNavigation();
  const dispatch = useDispatch();

  const handleLogout = () => {
    dispatch(logout());
  };

  const handleNavigate = (route) => {
    if (route === 'Dashboard') {
      navigation.navigate(ROUTES.DASHBOARD);
    } else if (route === 'Profile') {
      navigation.navigate(ROUTES.PROFILE);
    } else if (route === 'Appointments') {
      navigation.navigate(ROUTES.APPOINTMENTS);
    } else if (route === 'Settings') {
      navigation.navigate(ROUTES.SETTINGS);
    }
  };

  // Mock pets data
  const pets = [];

  return (
    <View style={styles.container}>
      {isTablet && (
        <Sidebar
          activeRoute="MyPets"
          onNavigate={handleNavigate}
          onLogout={handleLogout}
        />
      )}

      <View style={styles.mainContent}>
        <View style={styles.header}>
          <Text style={styles.headerTitle}>My Pets</Text>
          <TouchableOpacity style={styles.addButton}>
            <Text style={styles.addButtonText}>+ Add Pet</Text>
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.scrollContent} showsVerticalScrollIndicator={false}>
          <View style={styles.titleSection}>
            <Text style={styles.pageTitle}>Your Furry Friends</Text>
            <Text style={styles.pageSubtitle}>
              Manage your pets and their grooming preferences
            </Text>
          </View>

          {/* Pets List */}
          {pets.length === 0 ? (
            <View style={styles.emptyState}>
              <View style={styles.emptyIcon}>
                <Text style={styles.emptyIconText}>🐾</Text>
              </View>
              <Text style={styles.emptyTitle}>No Pets Added Yet</Text>
              <Text style={styles.emptySubtitle}>
                Add your furry companions to easily book appointments and track their grooming history.
              </Text>
              <CustomButton
                label="Add Your First Pet"
                onPress={() => {}}
                buttonStyle={styles.addPetButton}
                textStyle={styles.addPetButtonText}
              />
            </View>
          ) : (
            <View style={styles.petsList}>
              {/* Pets would be mapped here */}
            </View>
          )}

          {/* Pet Types Info */}
          <View style={styles.infoSection}>
            <Text style={styles.infoTitle}>We Groom All Types of Pets</Text>
            <View style={styles.petTypesRow}>
              <View style={styles.petTypeCard}>
                <Text style={styles.petTypeIcon}>🐕</Text>
                <Text style={styles.petTypeLabel}>Dogs</Text>
              </View>
              <View style={styles.petTypeCard}>
                <Text style={styles.petTypeIcon}>🐈</Text>
                <Text style={styles.petTypeLabel}>Cats</Text>
              </View>
              <View style={styles.petTypeCard}>
                <Text style={styles.petTypeIcon}>🐰</Text>
                <Text style={styles.petTypeLabel}>Rabbits</Text>
              </View>
              <View style={styles.petTypeCard}>
                <Text style={styles.petTypeIcon}>🐹</Text>
                <Text style={styles.petTypeLabel}>Others</Text>
              </View>
            </View>
          </View>
        </ScrollView>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    flexDirection: 'row',
    backgroundColor: '#F8F9FA',
  },
  mainContent: {
    flex: 1,
    backgroundColor: '#F8F9FA',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 24,
    paddingVertical: 16,
    backgroundColor: '#FFFFFF',
    borderBottomWidth: 1,
    borderBottomColor: '#E8E8E8',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: '700',
    color: '#333333',
  },
  addButton: {
    backgroundColor: '#6B8BB8',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 20,
  },
  addButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  scrollContent: {
    flex: 1,
    padding: 24,
  },
  titleSection: {
    marginBottom: 24,
  },
  pageTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: '#333333',
    marginBottom: 8,
  },
  pageSubtitle: {
    fontSize: 14,
    color: '#6B8BB8',
  },
  emptyState: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 48,
    alignItems: 'center',
    marginBottom: 24,
  },
  emptyIcon: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#F0F4F8',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 24,
  },
  emptyIconText: {
    fontSize: 36,
  },
  emptyTitle: {
    fontSize: 20,
    fontWeight: '600',
    color: '#333333',
    marginBottom: 8,
  },
  emptySubtitle: {
    fontSize: 14,
    color: '#666666',
    textAlign: 'center',
    marginBottom: 24,
    maxWidth: 300,
  },
  addPetButton: {
    backgroundColor: '#6B8BB8',
    paddingVertical: 14,
    paddingHorizontal: 32,
    borderRadius: 25,
  },
  addPetButtonText: {
    color: '#FFFFFF',
    fontSize: 14,
    fontWeight: '600',
  },
  petsList: {
    gap: 16,
    marginBottom: 24,
  },
  infoSection: {
    backgroundColor: '#FFFFFF',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#E8E8E8',
    padding: 24,
  },
  infoTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#333333',
    textAlign: 'center',
    marginBottom: 20,
  },
  petTypesRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  petTypeCard: {
    alignItems: 'center',
  },
  petTypeIcon: {
    fontSize: 32,
    marginBottom: 8,
  },
  petTypeLabel: {
    fontSize: 14,
    color: '#666666',
  },
});

export default MyPetsScreen;
